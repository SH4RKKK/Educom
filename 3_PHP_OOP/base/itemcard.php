<?php
require_once '../utility/htmlelements.php';
require_once '../traits/formhandler.php';
require_once '../forms/cardform.php';
require_once 'item.php';

class ItemCard {
    use FormHandler;
    private Item $item;
    
    // PUBLIC
    public function __construct(Item $item) {
        $this->item = $item;
    }

    private function makeCardForm(): void {
        !empty($_SESSION['logged_in']) ? $this->form = new CardForm($this->item->getId()) : $this->form = null;
    }

    public final function show(): void {
        $this->render();
    }

    private function render(): void {
        HtmlBuilder::openDiv('card');

        HtmlBuilder::openLink('product', 'card-link', ['id' => $this->item->getId()]);
        HtmlBuilder::loadImage($this->item->getImagePath(), $this->item->getName());
        
        HtmlBuilder::openDiv('card-content');
        
        HtmlBuilder::showMessage($this->item->getName().' - €'.number_format($this->item->getPrice(),2,',',''));

        HtmlBuilder::closeDiv();
        HtmlBuilder::closeLink();

        HtmlBuilder::openDiv('actions');

        $this->makeCardForm();
        if($this->form !== null) {
            $this->renderForm();
        } else {
            HtmlBuilder::openLink('login');
            echo 'Login om te bestellen!';
            HtmlBuilder::closeLink();
        }
        HtmlBuilder::closeDiv();
        HtmlBuilder::closeDiv();
    }
}
?>