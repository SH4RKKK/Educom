<?php
require_once '../abstract/body.php';
require_once '../menu/MainMenu.php';

class About extends BodyContent {
    private string $title;
    private string $classTitle;
    private Menu $menu;
    
    protected function initialize(): void {
        $this->title = !empty($_SESSION['logged_in']) ? 'Hello ' . StringHelper::escape($_SESSION['username']) : 'Hello Stranger';
        $this->classTitle = 'title';
        $this->menu = new MainMenu();
    }
    
    protected function render(): void {
        HtmlBuilder::showTitle($this->title, $this->classTitle);
        $this->menu->show();
        HtmlBuilder::showMessage('Ik ben Saman en ik vind software development leuk.');
        HtmlBuilder::showMessage('In mijn vrije tijd doe ik veel aan sporten zoals powerliften, streetliften, en calisthenics.');
        HtmlBuilder::showMessage('Daarnaast game ik ook nog als er vrije tijd over blijft!');
    }
}
?>