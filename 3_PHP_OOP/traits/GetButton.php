<?php
trait GetButton {
    protected string $btnMsg,$btnPage,$btnClass;
    
    protected function setGetButton(string $text, string $page, string $class = ''): void {
        $this->btnMsg = $text;
        $this->btnPage = $page;
        $this->btnClass = $class;
    }

    protected function renderGetButton(): void {
        HtmlBuilder::openLink($this->btnPage, $this->btnClass ?? '');
        echo $this->btnMsg ?? '';
        HtmlBuilder::closeLink();
    }
}