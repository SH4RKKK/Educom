<?php
require_once '../abstract/table.php';

class CartTable extends Table {
    private $total = 0;

    protected function initialize(): void {
        $this->tableClass = 'cart-table';
        $this->headers = ['Product', 'Prijs', 'Aantal', 'Subtotaal'];
    }
    protected function makeTableCell(string $data = '', string $class = '', string $span = '', ?Item $item = null): void {
        $this->openTableCell($class);
        if (!empty($item))HtmlBuilder::loadImage($item->getImagePath(), $item->getName());
        echo HtmlBuilder::escape($data);
        if (!empty($span)) HtmlBuilder::showSpan($span);
        $this->closeTableCell();
    }
    
    protected function renderRow($item): void {
        $subtotal = $item->getPrice() * $item->getAmount();
        $this->total += $subtotal;
        
        $this->makeTableCell('', 'cart-product', $item->getName(), $item);
        $this->makeTableCell('€' . number_format($item->getPrice(), 2, ',', '.'));
        $this->makeTableCell((string)(int)$item->getAmount());
        $this->makeTableCell('€' . number_format($subtotal, 2, ',', '.'));
    }
    
    public function getTotal(): float {
        return $this->total;
    }
}
?>