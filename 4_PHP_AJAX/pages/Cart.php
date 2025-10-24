<?php
require_once '../base/BodyContent.php';
require_once '../base/Item.php';
require_once '../table/CartTable.php';
require_once '../base/CartSummary.php';

class Cart extends BodyContent {
    private CartTable $table;
    private CartSummary $summary;
    private float $shipping;
    private string $cartTitle,$wrapperClass,$emptyCartMsg,$message;

    public function __construct(array $products,$message) {
        $this->table = new CartTable($products);
        $this->message = $message;
        parent::__construct();
    }
    
    protected function initialize(): void {
        parent::initialize();
        $this->shipping = 5.95;
        $this->cartTitle = 'Winkel mandje';
        $this->wrapperClass = 'cart-wrapper';
        $this->emptyCartMsg = 'Geen producten in winkelmandje';
        $this->table->calculateTotal();
        $this->summary = new CartSummary($this->table->getTotal(),$this->shipping);
    }

    protected function renderBody(): void {
        parent::renderBody();
        
        HtmlBuilder::openDiv($this->wrapperClass);

        if(!empty($this->message)) {
            HtmlBuilder::showTitle($this->message);
        } elseif($this->table->hasData()) {
            HtmlBuilder::showTitle($this->emptyCartMsg);
        } else {
            HtmlBuilder::showTitle($this->cartTitle);
            $this->table->render();
            $this->summary->renderSummary();
        }
        
        HtmlBuilder::closeDiv();
    }
}