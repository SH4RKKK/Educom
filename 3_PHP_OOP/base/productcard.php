<?php
require_once '../abstract/ItemCard.php';

class ProductCard extends ItemCard {
    private string $cardClass,$cardContentClass,$cardTitleClass,$cardPriceClass,$cardDescriptionClass,$cardActionClass;

    protected function initialize(): void {
        $this->cardClass = 'product-detail';
        $this->cardContentClass = 'product-image';
        $this->cardTitleClass = 'product-title';
        $this->cardPriceClass = 'product-price';
        $this->cardDescriptionClass = 'product-description';
        $this->cardActionClass = 'action-container';

        $this->setGetButton('Login om te bestellen!','login');
    }
    
    protected function render(): void {
        HtmlBuilder::openDiv($this->cardClass);
        HtmlBuilder::loadImage($this->item->getImagePath(), $this->item->getName(), $this->cardContentClass);
        HtmlBuilder::showTitle($this->item->getName(),$this->cardTitleClass);

        HtmlBuilder::showMessage('€' . number_format($this->item->getPrice(), 2, ',', ''),$this->cardPriceClass);
        if(!empty($this->item->getDescription())) HtmlBuilder::showMessage($this->item->getDescription(),$this->cardDescriptionClass);

        HtmlBuilder::openDiv( $this->cardActionClass);
        $this->form !== null ? $this->form->renderForm() : $this->renderGetButton();
        HtmlBuilder::closeDiv();
        
        HtmlBuilder::closeDiv();
    }
}