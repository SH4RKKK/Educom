<?php
require_once '../utility/htmlelements.php';
require_once '../traits/postbutton.php';
require_once '../validators/formvalidator.php';

abstract class Form {
    use PostButton;
    
    protected string $formClass, $formTitle;
    protected array $fields, $postData;
    protected ?FormValidator $validator = null;

    public function __construct(array $postData = []) {
        $this->postData = $postData;
    }

    public function renderForm(): void {
        $this->validator = new FormValidator($this->fields, $this->postData);
        $this->validator->validate();
        $this->render();
    }
    
    abstract protected function render(): void;
    abstract protected function renderField(array $field): void;
    
    protected function renderFields(): void {
        foreach ($this->fields as $field) {
            $this->renderField($field);
        }
    }

    protected function isValidated(): bool {
        return $this->validator !== null && $this->validator->isValid();
    }

    protected function getEmptyFields(): array {
        return $this->validator?->getEmptyFields() ?? [];
    }

    protected function getFieldMap(): array {
        return $this->validator?->getFieldMap() ?? [];
    }

    // BASIC FORM ELEMENTS
    protected function openForm(string $class = '', string $method = 'post', string $action = 'index.php'): void {
        echo '<form method="' . HtmlBuilder::escape($method) . '" action="' . HtmlBuilder::escape($action) . '"' .
             ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . '>';
    }
    
    protected function closeForm(): void {
        echo '</form>';
    }
    
    protected function openLegend(): void {
        echo '<legend>';
    }

    protected function closeLegend(): void {
        echo '</legend>';
    }

    private function makeHiddenField(string $name, string $value): void {
        echo '<input type="hidden" name="' . HtmlBuilder::escape($name) . '" value="' . HtmlBuilder::escape($value) . '">';
    }
    
    private function makeTextField(string $name, string $value = '', string $class = ''): void {
        echo '<input type="text" name="' . HtmlBuilder::escape($name) . '" value="' . HtmlBuilder::escape($value) . '"' .
             ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . '>';
    }
    
    private function makePasswordField(string $name, string $value = '', string $class = ''): void {
        echo '<input type="password" name="' . HtmlBuilder::escape($name) . '" value="' . HtmlBuilder::escape($value) . '" autocomplete="new-password"' .
             ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . '>';
    }
    
    private function makeNumberField(string $name, int $min = 1, int $value = 1, string $class = ''): void {
        echo '<input type="number" name="' . HtmlBuilder::escape($name) . '" min="' . max(1,$min) . '" value="' . max(1,$value) . '"' .
             ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . '>';
    }
    
    private function makeTextArea(string $name, string $value = '', string $class = '', string $rows = '5'): void {
        echo '<textarea name="' . HtmlBuilder::escape($name) . '"' .
             ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . 
             ' rows="'. $rows .'">' . 
             HtmlBuilder::escape($value) . 
             '</textarea>';
    }
    
    protected function makeEmailField(string $name, string $value = '', string $class = ''): void {
        echo '<input type="email" name="' . HtmlBuilder::escape($name) . '" value="' . HtmlBuilder::escape($value) . '"' .
             ($class ? ' class="' . HtmlBuilder::escape($class) . '"' : '') . '>';
    }

    protected function makeLabel(string $for, string $text): void {
        echo '<label for="' . HtmlBuilder::escape($for) . '">' . HtmlBuilder::escape($text) . ':</label>';
    }

    protected function renderInput(string $name, string $type, string $value = '', string $class = ''): void {
        match($type) {
            'textarea' => $this->makeTextArea($name, $value, $class),
            'email' => $this->makeEmailField($name, $value, $class),
            'password' => $this->makePasswordField($name, $value, $class),
            'number' => $this->makeNumberField($name, 1, (int)$value, $class),
            'hidden' => $this->makeHiddenField($name, $value),
            default => $this->makeTextField($name, $value, $class)
        };
    }
}
?>