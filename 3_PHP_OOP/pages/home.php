<?php
require_once '../base/body.php';
require_once '../base/MainMenu.php';
require_once '../traits/bodymessage.php';

class Home extends BodyContent {
    use BodyMessage;
    
    protected function initialize(): void {
        parent::initialize();

        $this->bodyMessage = [['text' => 'Welkom op mijn eerste website']];
    }

    protected function render(): void {
        parent::render();
        $this->renderMessage();
    }
}
?>