<?php
require_once '../base/BodyContent.php';
require_once '../base/ProductCard.php';
require_once '../base/Item.php';

class Product extends BodyContent {
    private ?ProductCard $product;
    private string $errProductMsg,$errorMessage;

    public function __construct(?Item $product,string $errorMessage = '') {
        $product !== null ? $this->product = new ProductCard($product) : $this->product = null;
        $this->errorMessage = $errorMessage;
        parent::__construct();
    }
    
    protected function initialize(): void {
        parent::initialize();
        $this->errProductMsg = 'Product bestaat niet';
    }

    protected function renderBody(): void {
        parent::renderBody();
        $this->product === null ? HtmlBuilder::showTitle($this->errorMessage ?? $this->errProductMsg) : $this->product->render();
    }
}