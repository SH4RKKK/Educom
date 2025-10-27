<?php
require_once '../base/BodyContent.php';
require_once '../base/WebshopCard.php';
require_once '../base/Item.php';
require_once '../base/Pagination.php';

class Webshop extends BodyContent {
    private Pagination $pagination;

    private array $products,$productsToShow;
    private int $totalItems,$productPerPage;
    private string $noProductErrMsg,$errorMessage,$pageClass,$ratingMessage;

    public function __construct(array $items,int $productPerPage,string $errorMessage = '',string $ratingMessage = '') {
        $this->products = $items;
        $this->totalItems = count($items);
        $this->productPerPage = $productPerPage;
        $this->errorMessage = $errorMessage;
        $this->ratingMessage = $ratingMessage;
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

    protected function renderBody(): void {
        parent::renderBody();

        if (!empty($this->errorMessage)) {
            HtmlBuilder::showTitle($this->errorMessage);
            return;
        }

        $this->setProductsToShow();
        if(empty($this->products) || empty($this->productsToShow)) {
            HtmlBuilder::showTitle($this->noProductErrMsg);
            return;
        }

        HtmlBuilder::openDiv($this->pageClass);
        foreach ($this->productsToShow as $productData) {
            $card = new WebshopCard($productData['item'],$productData['can_rate'],$productData['rating_error'],$this->ratingMessage);
            $card->render();
        }
        HtmlBuilder::closeDiv();

        $this->pagination->render();
    }
}