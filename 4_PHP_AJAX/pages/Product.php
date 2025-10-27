<?php
require_once '../base/BodyContent.php';
require_once '../base/ProductCard.php';
require_once '../base/Item.php';

class Product extends BodyContent {
    private ?ProductCard $product;
    private string $errProductMsg,$errorMessage;

    public function __construct(?Item $item, string $errorMessage = '', bool $canRate = false, string $ratingError = '',string $ratingMessage = '') {
        $item !== null ? $this->product = new ProductCard($item, $canRate, $ratingError, $ratingMessage) : $this->product = null;
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