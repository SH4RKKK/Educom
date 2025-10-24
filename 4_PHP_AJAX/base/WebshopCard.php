<?php
require_once '../abstract/ItemCard.php';

class WebshopCard extends ItemCard {
    private string $cardLinkClass,$productPage;

    protected function initialize(): void {
        $this->cardClass = 'card';
        $this->cardContentClass = 'card-content';
        $this->cardLinkClass = 'card-link';
        $this->cardActionClass = 'actions';

        $this->productPage = 'product';

        $this->setGetButton('Login om te bestellen!','login');
    }

    protected function renderItemCard(): void {
        HtmlBuilder::openDiv($this->cardClass);

        HtmlBuilder::openLink($this->productPage, $this->cardLinkClass, ['id' => $this->item->getId()]);
        
        HtmlBuilder::loadImage($this->item->getImagePath(), $this->item->getName());

        HtmlBuilder::openDiv( $this->cardContentClass);
        HtmlBuilder::showMessage($this->item->getName().' - €'.number_format($this->item->getPrice(),2,',',''));
        HtmlBuilder::closeDiv();

        HtmlBuilder::closeLink();

        //Rating render here
        
        HtmlBuilder::openDiv($this->cardActionClass);
        $this->form !== null ? $this->form->render() : $this->renderGetButton();
        HtmlBuilder::closeDiv();

        HtmlBuilder::closeDiv();
    }
}