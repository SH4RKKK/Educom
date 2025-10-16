<?php
require_once '../utility/htmlelements.php';
require_once 'menu.php';

abstract class BodyContent {
    // PUBLIC
    public function __construct() {
        $this->initialize();
    }
    
    // Main render
    public final function show(): void {
        $this->render();
    }

    // ABSTRACT
    abstract protected function initialize(): void;
    abstract protected function render(): void;
}
?>