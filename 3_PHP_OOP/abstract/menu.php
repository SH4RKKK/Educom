<?php
require_once '../utility/htmlelements.php';

abstract class Menu {
    
    // PROTECTED
    protected $menuClass = '';
    protected $menuItems = [];
    
    // PUBLIC
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
    
    // PROTECTED -- could be private?
    protected function openUnorderedList(string $class = ''): void {
        echo '<ul' . ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }
    
    protected function closeUnorderedList(): void {
        echo '</ul>';
    }
    
    protected function openList(): void {
        echo '<li>';
    }
    
    protected function closeList(): void {
        echo '</li>';
    }
}
?>