<?php
require_once '../abstract/body.php';
require_once '../menu/MainMenu.php';

require_once '../traits/formhandler.php';
require_once '../forms/contact.php';

class Contact extends BodyContent {
    use FormHandler;
    private string $title;
    private string $classTitle;
    private Menu $menu;

    protected function initialize(): void {
        $this->title = !empty($_SESSION['logged_in']) ? 'Hello ' . StringHelper::escape($_SESSION['username']) : 'Hello Stranger';
        $this->classTitle = 'title';
        $this->menu = new MainMenu();
        $this->form = new ContactForm();
    }
    
    protected function render(): void {
        HtmlBuilder::showTitle($this->title, $this->classTitle);
        $this->menu->show();
        $this->form->render();
    }
}
?>