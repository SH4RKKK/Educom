<?php
require_once '../abstract/body.php';

require_once '../traits/menuhandler.php';
require_once '../base/MainMenu.php';

require_once '../traits/formhandler.php';
require_once '../forms/contactform.php';

require_once '../traits/title.php';

class Contact extends BodyContent {
    use FormHandler;
    use MenuHandler;
    use Title;

    protected function initialize(): void {
        $this->title = [
            'text' => !empty($_SESSION['logged_in']) ? 'Hello ' . HtmlBuilder::escape($_SESSION['username']) : 'Hello Stranger',
            'class' => 'title'
        ];

        $this->menu = new MainMenu();
        $this->form = new ContactForm($_POST);
    }
    
    protected function render(): void {
        $this->renderTitle();
        $this->renderMenu();
        $this->renderForm();
    }
}
?>