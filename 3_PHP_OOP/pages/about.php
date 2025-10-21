<?php
require_once '../base/bodycontent.php';
require_once '../base/MainMenu.php';
require_once '../traits/bodymessage.php';

class About extends BodyContent {
    use BodyMessage;
    
    protected function initialize(): void {
        parent::initialize();

        $this->bodyMessage = [
            ['text' => 'Ik ben Saman en ik vind software development leuk.'],
            ['text' => 'In mijn vrije tijd doe ik veel aan sporten zoals powerliften, streetliften, en calisthenics.'],
            ['text' => 'Daarnaast game ik ook nog als er vrije tijd over blijft!']
        ];
    }
    
    protected function render(): void {
        parent::render();
        $this->renderMessage();
    }
}
?>