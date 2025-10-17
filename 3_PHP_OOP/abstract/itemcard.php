<?php
require_once '../utility/htmlelements.php';

abstract class ItemCard {
    
    // PUBLIC
    public function __construct() {
        $this->initialize();
    }

    // ABSTRACT
    abstract protected function initialize(): void;
}
?>