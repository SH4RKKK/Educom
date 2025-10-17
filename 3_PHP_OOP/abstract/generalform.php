<?php
require_once '../abstract/Form.php';
require_once '../utility/stringhelper.php';
require_once '../traits/title.php';

abstract class GeneralForm extends Form {
    use Title;
    protected string $errMsg = 'Een of meerdere velden zijn leeg';
    abstract protected function initialize(): void;
    
    protected function render(): void {
        if (!$this->isValidated()) {
            $this->openForm($this->formClass);
            

            //Logic needs to be fixed later when having a validator class
            /*
                    $msg = $response['message'] ?: (!empty($response['empty']) 
                        ? 'Een of meerdere velden zijn leeg'
                        : 'Vul gegevens in om in contact te komen!!!');
                        
            */

            if (!empty($this->formTitle)) {
                $this->openLegend();
                empty($this->emptyFields) ? HtmlBuilder::showTitle($this->formTitle) : HtmlBuilder::showTitle($this->errMsg);
                $this->closeLegend();
            }

            $this->renderHiddenFields();
            $this->renderFields();
            $this->renderButtons();
            $this->closeForm();
        } else {
            $this->renderTitle();
        }
    }

    protected function renderFields(): void {
        foreach ($this->fields as $field) {
            $this->renderField($field);
        }
    }

    protected function renderField(array $field): void {
        $label = $field['label'];
        $type = $field['type'] ?? 'text';
        $name = $this->fieldMap[$label];
        
        $isEmpty = in_array($name, $this->emptyFields);
        $inputClass = $isEmpty ? 'error' : '';
        $value = $this->postData[$name] ?? '';
        
        $this->makeLabel($name, $label);
        $this->renderInput($name, $type, $value, $inputClass);
        
        if ($isEmpty) {
            HtmlBuilder::showSpan('Veld is leeg!', $inputClass);
        }
        
        HtmlBuilder::newLine();
    }

    protected function renderInput(string $name, string $type, string $value, string $class): void {
        match($type) {
            'textarea' => $this->makeTextArea($name, $value, $class),
            'email' => $this->makeEmailField($name, $value, $class),
            'password' => $this->makePasswordField($name, $value, $class),
            'number' => $this->makeNumberField($name, 1, (int)$value, $class),
            default => $this->makeTextField($name, $value, $class)
        };
    } 
}
?>