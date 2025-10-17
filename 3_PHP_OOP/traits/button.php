<?php
require_once '../utility/htmlelements.php';

trait Button {
    protected array $buttons = [];

    protected function addButton(string $text, string $name = '', string $value = '', string $class = ''): void
    {
        $this->buttons[] = [
            'text' => $text,
            'name' => $name,
            'value' => $value,
            'class' => $class
        ];
    }

    protected function renderButtons(): void
    {
        foreach ($this->buttons as $btn) {
            echo '<button type="submit"' .
                 ($btn['name'] ? ' name="' . HtmlBuilder::escape($btn['name']) . '"' : '') .
                 ($btn['value'] ? ' value="' . HtmlBuilder::escape($btn['value']) . '"' : '') .
                 ($btn['class'] ? ' class="' . HtmlBuilder::escape($btn['class']) . '"' : '') .
                 '>' .
                 HtmlBuilder::escape($btn['text']) .
                 '</button>';
        }
    }
}
?>