<?php
require_once '../base/bodycontent.php';
require_once '../base/item.php';
require_once '../table/carttable.php';
require_once '../base/cartsummary.php';

class Cart extends BodyContent {
    private CartTable $table;
    private CartSummary $summary;
    private float $shipping;
    private string $cartTitle,$wrapperClass;

    public function __construct(array $products) {
        //append amount to id before parsing
        $this->table = new CartTable($products);
        parent::__construct();
    }
    
    protected function initialize(): void {
        parent::initialize();

        $this->shipping = 5.95;
        $this->cartTitle = 'Winkel mandje';
        $this->wrapperClass = 'cart-wrapper';
        
        $this->table->calculateTotal();
        $this->summary = new CartSummary($this->table->getTotal(),$this->shipping);
    }

    protected function render(): void {
        parent::render();
        
        HtmlBuilder::openDiv($this->wrapperClass);
        HtmlBuilder::showTitle($this->cartTitle);
        $this->table->show();
        $this->summary->renderSummary();
        HtmlBuilder::closeDiv();
    }
}
?>