<?php
require_once '../abstract/Table.php';

class CartTable extends Table {
    private float $total = 0;
    private string $tableDivClass,$imageCellClass ;

    protected function initialize(): void {
        $this->tableClass = 'cart-table';
        $this->headers = ['Product', 'Prijs', 'Aantal', 'Subtotaal'];
        $this->tableDivClass = 'cart-items';
        $this->imageCellClass = 'cart-product';
    }
    protected function makeTableCell(string $data = '', string $class = '', string $span = '', ?Item $item = null): void {
        $this->openTableCell($class);
        if (!empty($item))HtmlBuilder::loadImage($item->getImagePath(), $item->getName());
        echo HtmlBuilder::escape($data);
        if (!empty($span)) HtmlBuilder::showSpan($span);
        $this->closeTableCell();
    }
    
    protected function renderRow($item): void {
        $this->makeTableCell('', $this->imageCellClass, $item->getName(), $item);
        $this->makeTableCell('€' . number_format($item->getPrice(), 2, ',', '.'));
        $this->makeTableCell((string)(int)$item->getAmount());
        $this->makeTableCell('€' . number_format($this->calculateSubtotal($item), 2, ',', '.'));
    }
    
    public function getTotal(): float {
        return $this->total;
    }

    public function show(): void {
        HtmlBuilder::openDiv($this->tableDivClass);
        parent::show();
        HtmlBuilder::closeDiv();
    }

    private function calculateSubtotal(Item $item): float {
        return $item->getPrice() * $item->getAmount();
    }

    public function calculateTotal(): void {
        $this->total = 0;
        
        foreach ($this->data as $item) {
            $this->total += $this->calculateSubtotal($item);
        }
    }
}