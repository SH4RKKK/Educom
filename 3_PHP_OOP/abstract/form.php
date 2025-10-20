<?php
require_once '../utility/htmlelements.php';
require_once '../traits/button.php';

abstract class Form {
    // PROTECTED
    use Button;
    protected string $formClass;
    protected string $formTitle;
    protected array $fields;
    protected array $fieldMap;
    protected array $postData;
    protected array $emptyFields;

    // PUBLIC 
    public function __construct(array $postData = []) {
        $this->postData = $postData;
        $this->initialize();
    }

    // Main render
    public function renderForm(): void {
        $this->render();
    }
    
    // ABSTRACT
    abstract protected function initialize(): void;
    abstract protected function render(): void;
    abstract protected function renderField(array $field): void;
    
    //HELPER FUNCTIONS
    private function buildFieldMap(): array {
        $map = [];
        foreach ($this->fields as $field) {
            $label = $field['label'];
            $map[$label] = $this->slugify($label);
        }
        return $map;
    }

    protected function renderFields(): void {
        foreach ($this->fields as $field) {
            $this->renderField($field);
        }
    }

    // Internal validation
    private function validatePostData(): array {
        $empty = [];

        foreach ($this->postData as $key => $value) {
            if (trim($value) === '') {
                $empty[] = $key;
            }
        }

        return $empty;
    }

    protected function isValidated(): bool {
        return empty($this->emptyFields) && !empty($this->postData);
    }

    public final function setEmptyFields(): void {
        $this->emptyFields = $this->validatePostData();
    }

    public final function setFieldMap(): void {
        $this->fieldMap = $this->buildFieldMap();
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

    private function slugify(string $text): string {
        $text = strtolower(trim($text));
        $text = str_replace([' ', '-', '_'], '', $text);
        $text = preg_replace('/[^a-z0-9]/', '', $text);
        return $text;
    }
}
?>