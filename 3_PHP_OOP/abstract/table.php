<?php
require_once '../utility/htmlelements.php';

abstract class Table {
    protected $tableClass;
    protected $headers = [];
    protected $data = [];
    
    public function __construct(array $data = []) {
        $this->data = $data;
        $this->initialize();
    }
    
    // Main render
    public final function show(): void {
        $this->openTable($this->tableClass);
        $this->renderHeader();
        $this->renderBody();
        $this->closeTable();
    }
    
    // ABSTRACT
    abstract protected function initialize(): void;
    abstract protected function renderRow(array $item): void;
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
    
    // PRIVATE
    protected function openTableCell(string $class = ''): void {
        echo '<td' . ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . '>';
    }
    
    protected function closeTableCell(): void {
        echo '</td>';
    }

    private function openTable(string $class = ''): void {
        echo '<table' . ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . '>';
    }
    
    private function closeTable(): void {
        echo '</table>';
    }
    
    private function openTableHead(): void {
        echo '<thead>';
    }
    
    private function closeTableHead(): void {
        echo '</thead>';
    }
    
    private function openTableBody(): void {
        echo '<tbody>';
    }
    
    private function closeTableBody(): void {
        echo '</tbody>';
    }
    
    private function openTableRow(): void {
        echo '<tr>';
    }
    
    private function closeTableRow(): void {
        echo '</tr>';
    }
    
    private function makeTableHeaderCell(string $content, string $class = ''): void {
        echo '<th' . ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . '>' . 
             HtmlBuilder::escape($content) . '</th>';
    }
}
?>