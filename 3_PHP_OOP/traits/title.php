<?php
trait Title {
    protected array $title;

    private function renderTitle(): void {
        HtmlBuilder::showTitle($this->title['text'], $this->title['class'] ?? '');
    }
}
?>