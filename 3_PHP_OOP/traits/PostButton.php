<?php
require_once '../utility/HtmlBuilder.php';

trait PostButton {
    protected string $btnMsg,$btnName,$btnValue,$btnClass;
    
    protected function setPostButton(string $text, string $name = '', string $value = '', string $class = ''): void {
        $this->btnMsg = $text;
        $this->btnName = $name;
        $this->btnValue = $value;
        $this->btnClass = $class;
    }

    protected function renderPostButton(): void {
        echo '<button type="submit"' .
             ($this->btnName ? ' name="' . HtmlBuilder::escape($this->btnName) . '"' : '') .
             ($this->btnValue ? ' value="' . HtmlBuilder::escape($this->btnValue) . '"' : '') .
             ($this->btnClass ? ' class="' . HtmlBuilder::escape($this->btnClass) . '"' : '') .
             '>' .
             HtmlBuilder::escape($this->btnMsg) .
             '</button>';
    }
}