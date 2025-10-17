<?php
require_once '../abstract/body.php';

require_once '../traits/menuhandler.php';
require_once '../base/MainMenu.php';

require_once '../traits/bodymessage.php';

require_once '../traits/title.php';

class Home extends BodyContent {
    use MenuHandler;
    use BodyMessage;
    use Title;
    
    protected function initialize(): void {
        $this->title = [
            'text' => !empty($_SESSION['logged_in']) ? 'Hello ' . HtmlBuilder::escape($_SESSION['username']) : 'Hello Stranger',
            'class' => 'title'
        ];

        $this->menu = new MainMenu();
        $this->bodyMessage = [
            ['text' => 'Welkom op mijn eerste website']
        ];
    }

    protected function render(): void {
        $this->renderTitle();
        $this->renderMenu();
        $this->renderMessage();
    }
}
?>