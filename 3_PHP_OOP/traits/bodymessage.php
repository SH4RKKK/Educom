<?php
trait BodyMessage {
    private array $bodyMessage;
    
    private function renderMessage(): void {
        foreach ($this->bodyMessage as $messageData) {
            HtmlBuilder::showMessage($messageData['text'], $messageData['class'] ?? '');
        }
    }
}