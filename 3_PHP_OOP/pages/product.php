<?php
require_once '../abstract/body.php';

require_once '../traits/menuhandler.php';
require_once '../base/MainMenu.php';

require_once '../traits/title.php';

require_once '../forms/cardform.php';
require_once '../traits/formhandler.php';

require_once '../base/item.php';

class Product extends BodyContent {
    use MenuHandler;
    use Title;
    use FormHandler;

    private ?Item $product;

    public function __construct(?Item $product) {
        $product !== null ? $this->product = $product : $this->product = null;
        if(!empty($_SESSION['logged_in'])) $this->form = new CardForm($this->product->getId());
        parent::__construct();
    }
    
    protected function initialize(): void {
        $this->title = [
            'text' => !empty($_SESSION['logged_in']) ? 'Hello ' . HtmlBuilder::escape($_SESSION['username']) : 'Hello Stranger',
            'class' => 'title'
        ];

        $this->menu = new MainMenu();
    }

    protected function render(): void {
        $this->renderTitle();
        $this->renderMenu();

        if($this->product === null) {
            $this->title = ['text' => 'Product bestaat niet', 'class' => 'title'];
            $this->renderTitle();
            return;
        }

        HtmlBuilder::openDiv('product-detail'); //make variable
        HtmlBuilder::loadImage($this->product->getImagePath(), $this->product->getName(), 'product-image');

        $this->title = ['text' => $this->product->getName(), 'class' => 'product-title'];
        $this->renderTitle();

        HtmlBuilder::showMessage('€' . number_format($this->product->getPrice(), 2, ',', ''),'product-price');
        if(!empty($this->product->getDescription())) HtmlBuilder::showMessage($this->product->getDescription(),'product-description');

        HtmlBuilder::openDiv('action-container');
        if($this->form !== null) {
            $this->renderForm();
        } else {
            HtmlBuilder::openLink('login');
            echo 'Login om te bestellen!';
            HtmlBuilder::closeLink();
        }
        HtmlBuilder::closeDiv();
    }
}
?>