<?php
require_once '../abstract/Form.php';
require_once '../traits/title.php';

abstract class GeneralForm extends Form {
    use Title;
    protected string $errMsg = 'Een of meerdere velden zijn leeg';
    abstract protected function initialize(): void;
    
    protected function render(): void {
        if ($this->isValidated()) {
            $this->renderTitle();
            return;
        }

        $this->openForm($this->formClass);
        $this->openLegend();
        if (!empty($this->emptyFields)) $this->formTitle = $this->errMsg;
        HtmlBuilder::showTitle($this->formTitle);
        $this->closeLegend();

        $this->renderFields();
        $this->renderButtons();
        $this->closeForm();


        //Logic needs to be fixed later when having a validator class
        /*
                $msg = $response['message'] ?: (!empty($response['empty']) 
                    ? 'Een of meerdere velden zijn leeg'
                    : 'Vul gegevens in om in contact te komen!!!');
                    
        */
    }

    protected function renderField(array $field): void {
        $label = $field['label'];
        $type = $field['type'] ?? 'text';
        $name = $this->fieldMap[$label];
        
        $isEmpty = in_array($name, $this->emptyFields);
        $inputClass = $isEmpty ? 'error' : '';
        $value = !empty($this->postData[$name]) ? $this->postData[$name] : ($field['value'] ?? '');
        
        if($field['type'] !== 'hidden') $this->makeLabel($name, $label);
        $this->renderInput($name, $type, $value, $inputClass);
        
        if ($isEmpty) {
            HtmlBuilder::showSpan('Veld is leeg!', $inputClass);
        }
        
        HtmlBuilder::newLine();
    }
}
?>