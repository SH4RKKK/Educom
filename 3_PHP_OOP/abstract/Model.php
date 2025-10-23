<?php
require_once '../database/Database.php';
abstract class Model {
    protected $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

}