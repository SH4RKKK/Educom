<?php
require_once '../utility/htmlelements.php';

abstract class Form {
    
    // PROTECTED
    protected $formClass = '';
    protected $formTitle = '';
    protected $fields = [];
    protected $hiddenFields = [];
    protected $postData = [];
    protected $emptyFields = [];

    // Need to make seperate button class, much simpler
    protected $buttonText = '';
    protected $buttonName = '';
    protected $buttonValue = '';
    protected $buttonClass = '';

    // PUBLIC 
    public function __construct(array $postData = [], array $emptyFields = []) {
        $this->postData = $postData;
        $this->emptyFields = $emptyFields;
        $this->initialize();
    }
    
    // Main render
    public function render(): void {
        $this->openForm($this->formClass);
        
        // Form title
        if (!empty($this->formTitle)) {
            $this->openLegend();
            HtmlBuilder::showTitle($this->formTitle);
            $this->closeLegend();
        }
        
        // Children implement their own field rendering
        $this->renderFields();
        
        // Submit button
        $this->makeButton($this->buttonText, $this->buttonName, $this->buttonValue, $this->buttonClass);
        
        $this->closeForm();
    }
    
    // ABSTRACT
    abstract protected function initialize(): void;
    abstract protected function renderFields(): void;
    
    // PROTECTED HELPERS -- Ignore helper function for now, its translated template of my procedural function
    protected function renderField(string $field): void {
        $cleanedField = StringHelper::slugify($field);
        $isEmpty = in_array($cleanedField, $this->emptyFields);
        $inputClass = $isEmpty ? 'error' : '';
        $value = $this->postData[$cleanedField] ?? '';
        
        // Label
        if ($this->showLabel) {
            $this->makeLabel($cleanedField, $field);
        }
        
        // Field input - detect and render
        if (str_contains($cleanedField, 'wachtwoord')) {
            $this->makePasswordField($cleanedField, $value, $inputClass);
        } elseif (str_contains($cleanedField, 'amount')) {
            $this->makeNumberField($cleanedField, 1, 1, $inputClass);
        } else {
            $this->makeTextField($cleanedField, $value, $inputClass);
        }
        
        // Error message
        if ($isEmpty) {
            HtmlBuilder::showSpan('Veld is leeg!', 'error');
        }
        
        // Newline
        if ($this->addNewline) {
            HtmlBuilder::newLine();
        }
    }
    
    // BASIC FORM ELEMENTS
    protected function openForm(string $class = '', string $method = 'post', string $action = 'index.php'): void {
        echo '<form method="' . StringHelper::escape($method) . '" action="' . StringHelper::escape($action) . '"' .
             ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
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

    protected function makeHiddenField(string $name, string $value): void {
        echo '<input type="hidden" name="' . StringHelper::escape($name) . '" value="' . StringHelper::escape($value) . '">';
    }
    
    protected function makeTextField(string $name, string $value = '', string $class = ''): void {
        echo '<input type="text" name="' . StringHelper::escape($name) . '" value="' . StringHelper::escape($value) . '"' .
             ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }
    
    protected function makePasswordField(string $name, string $value = '', string $class = ''): void {
        echo '<input type="password" name="' . StringHelper::escape($name) . '" value="' . StringHelper::escape($value) . '" autocomplete="new-password"' .
             ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }
    
    protected function makeNumberField(string $name, int $min = 1, int $value = 1, string $class = ''): void {
        echo '<input type="number" name="' . StringHelper::escape($name) . '" min="' . $min . '" value="' . $value . '"' .
             ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }
    
    protected function makeTextArea(string $name, string $value = '', string $class = '', string $rows = '5'): void {
        echo '<textarea name="' . StringHelper::escape($name) . '"' .
             ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . 
             ' rows="'. $rows .'">' . 
             StringHelper::escape($value) . 
             '</textarea>';
    }
    
    protected function makeEmailField(string $name, string $value = '', string $class = ''): void {
        echo '<input type="email" name="' . StringHelper::escape($name) . '" value="' . StringHelper::escape($value) . '"' .
             ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>';
    }

    protected function makeLabel(string $for, string $text): void {
        echo '<label for="' . StringHelper::escape($for) . '">' . StringHelper::escape($text) . ':</label>';
    }
    
    protected function makeButton(string $text, string $name = '', string $value = '', string $class = ''): void {
        echo '<button type="submit"' .
             ($name ? ' name="' . StringHelper::escape($name) . '"' : '') .
             ($value ? ' value="' . StringHelper::escape($value) . '"' : '') .
             ($class ? ' class="' . StringHelper::escape($class) . '"' : '') . '>' .
             StringHelper::escape($text) . '</button>';
    }
}
?>