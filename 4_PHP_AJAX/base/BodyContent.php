<?php
require_once '../utility/HtmlBuilder.php';
require_once '../base/MainMenu.php';

class BodyContent {
    protected MainMenu $menu;
    protected string $pageTitle,$titleClass;

    public function __construct() {
        $this->initialize();
    }
    
    public final function render(): void {
        $this->renderBody();
    }

    protected function initialize(): void {
        $this->pageTitle = !empty($_SESSION['logged_in']) ? 'Hello ' . HtmlBuilder::escape($_SESSION['username']) : 'Hello Stranger';
        $this->titleClass = 'title';
        $this->menu = new MainMenu();
    }
    
    protected function renderBody(): void {
        HtmlBuilder::showTitle($this->pageTitle,$this->titleClass);
        $this->menu->updateMenu();
        $this->menu->render();
    }
}