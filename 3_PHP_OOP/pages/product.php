<?php
require_once '../base/bodycontent.php';
require_once '../base/productcard.php';
require_once '../base/item.php';

class Product extends BodyContent {
    private ?ProductCard $product;
    private string $errProductMsg,$title;

    public function __construct(?Item $product) {
        $product !== null ? $this->product = new ProductCard($product) : $this->product = null;
        parent::__construct();
    }
    
    protected function initialize(): void {
        parent::initialize();

        $this->errProductMsg = 'Product bestaat niet';

    }

    protected function render(): void {
        parent::render();
        $this->product === null ? HtmlBuilder::showTitle($this->errProductMsg) : $this->product->show();
    }
}
?>