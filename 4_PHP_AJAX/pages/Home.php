<?php
require_once '../base/BodyContent.php';
require_once '../base/MainMenu.php';
require_once '../traits/BodyMessage.php';

class Home extends BodyContent {
    use BodyMessage;
    
    protected function initialize(): void {
        parent::initialize();
        $this->bodyMessage = [['text' => 'Welkom op mijn eerste website']];
    }

    protected function renderBody(): void {
        parent::renderBody();
        $this->renderMessage();
    }
}