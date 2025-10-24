<?php
require_once '../abstract/Table.php';

class CartTable extends Table {
    private float $total = 0;
    private string $tableDivClass,$imageCellClass;

    protected function initialize(): void {
        $this->tableClass = 'cart-table';
        $this->headers = ['Product', 'Prijs', 'Aantal', 'Subtotaal'];
        $this->tableDivClass = 'cart-items';
        $this->imageCellClass = 'cart-product';
    }

    private function makeTableCell(string $data = '', string $class = '', string $span = '', ?Item $item = null): void {
        $this->openTableCell($class);
        if (!empty($item))HtmlBuilder::loadImage($item->getImagePath(), $item->getName());
        echo HtmlBuilder::escape($data);
        if (!empty($span)) HtmlBuilder::showSpan($span);
        $this->closeTableCell();
    }
    
    protected function renderRow(array $cartItem): void {
        $this->makeTableCell('', $this->imageCellClass, $cartItem['item']->getName(), $cartItem['item']);
        $this->makeTableCell('€' . number_format($cartItem['item']->getPrice(), 2, ',', '.'));
        $this->makeTableCell((string)(int)$cartItem['amount']);
        $this->makeTableCell('€' . number_format($this->calculateSubtotal($cartItem), 2, ',', '.'));
    }
    
    public final function getTotal(): float {
        return $this->total;
    }

    public final function render(): void {
        HtmlBuilder::openDiv($this->tableDivClass);
        parent::render();
        HtmlBuilder::closeDiv();
    }

    private function calculateSubtotal(array $cartItem): float {
        return $cartItem['item']->getPrice() * $cartItem['amount'];
    }

    public final function calculateTotal(): void {
        $this->total = 0;
        foreach ($this->data as $item) {
            $this->total += $this->calculateSubtotal($item);
        }
    }
}