<?php
require_once 'HtmlBuilder.php';
require_once 'StringHelper.php';

abstract class Table {
    
    // PROTECTED - Children can access
    protected $tableClass = '';
    protected $headers = [];
    protected $data = [];
    
    // PUBLIC
    public function __construct(array $data = []) {
        $this->data = $data;
        $this->initialize();
    }
    
    // Main render
    public function show(): void {
        $this->openTable($this->tableClass);
        $this->renderHeader();
        $this->renderBody();
        $this->closeTable();
    }
    
    // ABSTRACT
    abstract protected function initialize(): void;
    abstract protected function renderRow($item): void;
    
    // PROTECTED
    protected function renderHeader(): void {
        if (empty($this->headers)) return;
        
        $this->openTableHead();
        $this->openTableRow();
        
        foreach ($this->headers as $header) {
            $this->makeTableHeaderCell($header);
        }
        
        $this->closeTableRow();
        $this->closeTableHead();
    }
    
    protected function renderBody(): void {
        $this->openTableBody();
        
        foreach ($this->data as $item) {
            $this->openTableRow();
            $this->renderRow($item);
            $this->closeTableRow();
        }
        
        $this->closeTableBody();
    }
    
    // PROTECTED -- maybe privated?
    protected function openTable(string $class = ''): void {
        echo '<table' . ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }
    
    protected function closeTable(): void {
        echo '</table>';
    }
    
    protected function openTableHead(): void {
        echo '<thead>';
    }
    
    protected function closeTableHead(): void {
        echo '</thead>';
    }
    
    protected function openTableBody(): void {
        echo '<tbody>';
    }
    
    protected function closeTableBody(): void {
        echo '</tbody>';
    }
    
    protected function openTableRow(): void {
        echo '<tr>';
    }
    
    protected function closeTableRow(): void {
        echo '</tr>';
    }
    
    protected function makeTableHeaderCell(string $content, string $class = ''): void {
        echo '<th' . ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>' . 
             StringHelper::escape($content) . '</th>';
    }
    
    protected function makeTableCell(string $data, string $class = ''): void {
        echo '<td' . ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>' . 
             StringHelper::escape($data) . '</td>';
    }
    
    protected function openTableCell(string $class = ''): void {
        echo '<td' . ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }
    
    protected function closeTableCell(): void {
        echo '</td>';
    }
}
/*
<?php
require_once 'Table.php';

class CartTable extends Table {
    
    private $total = 0;
    
    protected function initialize(): void {
        $this->tableClass = 'cart-table';
        $this->headers = ['Product', 'Prijs', 'Aantal', 'Subtotaal'];
    }
    
    protected function renderRow($item): void {
        $subtotal = $item['price'] * $item['amount'];
        $this->total += $subtotal;
        
        // Product cell with image
        $this->openTableCell('cart-product');
        HtmlBuilder::loadImage($item['image_path'], $item['name']);
        echo '<span>' . StringHelper::escape($item['name']) . '</span>';
        $this->closeTableCell();
        
        // Price
        $this->makeTableCell('€' . number_format($item['price'], 2, ',', '.'));
        
        // Amount
        $this->makeTableCell((string)(int)$item['amount']);
        
        // Subtotal
        $this->makeTableCell('€' . number_format($subtotal, 2, ',', '.'));
    }
    
    public function getTotal(): float {
        return $this->total;
    }
}
?>
*/
?>