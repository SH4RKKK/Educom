<?php
require_once '../utility/htmlelements.php';

abstract class Menu {
    protected string $menuClass;
    protected array $menuItems;
    
    public function __construct() {
        $this->initialize();
    }
    
    // Main render
    public function show(): void {
        $this->openUnorderedList($this->menuClass);
        
        foreach ($this->menuItems as $item) {
            $this->renderMenuItem($item);
        }
        
        $this->closeUnorderedList();
    }
    
    // ABSTRACT
    abstract protected function initialize(): void;
    abstract protected function renderMenuItem(string $item): void;
    
    private function openUnorderedList(string $class = ''): void {
        echo '<ul' . ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . '>';
    }
    
    private function closeUnorderedList(): void {
        echo '</ul>';
    }
    
    protected function openListItem(): void {
        echo '<li>';
    }
    
    protected function closeListItem(): void {
        echo '</li>';
    }
}
?>