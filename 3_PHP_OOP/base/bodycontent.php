<?php
require_once '../utility/HtmlBuilder.php';
require_once '../base/MainMenu.php';

class BodyContent {
    protected MainMenu $menu;
    protected string $pageTitle,$titleClass;

    // PUBLIC
    public function __construct() {
        $this->initialize();
    }
    
    // Main render
    public final function show(): void {
        $this->render();
    }

     protected function initialize(): void {
        $this->pageTitle = !empty($_SESSION['logged_in']) ? 'Hello ' . HtmlBuilder::escape($_SESSION['username']) : 'Hello Stranger';
        $this->titleClass = 'title';
        $this->menu = new MainMenu();
     }
    
    protected function render(): void {
        HtmlBuilder::showTitle($this->pageTitle,$this->titleClass);
        $this->menu->updateMenu();
        $this->menu->show();
    }
}