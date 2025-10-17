<?php
require_once '../utility/htmlelements.php';

abstract class Item {
    protected $id;
    protected $name;
    protected $description;
    protected $price;
    protected $imagePath;
    
    // PUBLIC
    public function __construct() {
        $this->initialize();
    }

    // ABSTRACT
    abstract protected function initialize(): void;
}
?>