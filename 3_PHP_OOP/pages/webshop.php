<?php
require_once '../abstract/body.php';

require_once '../traits/menuhandler.php';
require_once '../base/MainMenu.php';

require_once '../traits/paginationhandler.php';
require_once '../base/pagination.php';

require_once '../traits/title.php';

require_once '../base/itemcard.php';
require_once '../traits/cardhandler.php';

require_once '../base/item.php';

class Webshop extends BodyContent {
    use MenuHandler;
    use PangiationHandler;
    use Title;
    use CardHandler;

    private array $products;
    private array $productsToShow;
    private int $totalItems;
    private int $productPerPage;

    public function __construct(array $items,int $productPerPage) {
        $this->products = $items;
        $this->totalItems = count($items);
        $this->productPerPage = $productPerPage;

        parent::__construct();
    }
    
    protected function initialize(): void {
        $this->title = [
            'text' => !empty($_SESSION['logged_in']) ? 'Hello ' . HtmlBuilder::escape($_SESSION['username']) : 'Hello Stranger',
            'class' => 'title'
        ];

        $this->menu = new MainMenu();
        $this->pagination = new Pagination($this->totalItems,$this->productPerPage,'webshop','pagination', 'index');
        $this->setProductsToShow();
    }

    private function setProductsToShow(): void {
        $startIndex = ($this->getPage() - 1) * $this->productPerPage;
        $this->productsToShow = array_slice($this->products, $startIndex, $this->productPerPage);
    }

    protected function render(): void {
        $this->renderTitle();
        $this->renderMenu();
        HtmlBuilder::openDiv('page'); //make variable
        if(!empty($this->products)) {
            foreach ($this->productsToShow as $product) {
                $this->card = new ItemCard($product);
                $this->renderCard();
            }
        } else {
            HtmlBuilder::closeDiv();
            $this->title = ['text' => 'Geen producten te koop :(', 'class' => 'title'];
            $this->renderTitle();
            return;
        }
        
        HtmlBuilder::closeDiv();
        $this->renderPagination();
    }
}
?>