<?php
require_once '../base/body.php';
require_once '../base/webshopcard.php';
require_once '../base/item.php';
require_once '../base/pagination.php';

class Webshop extends BodyContent {
    private Pagination $pagination;
    private WebshopCard $card;

    private array $products,$productsToShow;
    private int $totalItems,$productPerPage;
    private string $noProductErrMsg,$pageClass;

    public function __construct(array $items,int $productPerPage) {
        $this->products = $items;
        $this->totalItems = count($items);
        $this->productPerPage = $productPerPage;

        parent::__construct();
    }
    
    protected function initialize(): void {
        parent::initialize();

        $this->noProductErrMsg = 'Geen producten te koop :(';
        $this->pageClass = 'page';
        $this->pagination = new Pagination($this->totalItems,$this->productPerPage,'webshop','pagination', 'index');
    }

    private function setProductsToShow(): void {
        $startIndex = ($this->pagination->getDisplayPage() - 1) * $this->productPerPage;
        $this->productsToShow = array_slice($this->products, $startIndex, $this->productPerPage);
    }

    protected function render(): void {
        parent::render();

        $this->setProductsToShow();
        if(empty($this->products) && empty($this->productsToShow)) {
            HtmlBuilder::showTitle($this->noProductErrMsg);
            return;
        }

        HtmlBuilder::openDiv($this->pageClass);
        foreach ($this->productsToShow as $product) {
            $this->card = new WebshopCard($product);
            $this->card->show();
        }
        HtmlBuilder::closeDiv();

        $this->pagination->show();
    }
}
?>