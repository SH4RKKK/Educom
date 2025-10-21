<?php
require_once '../abstract/Form.php';

abstract class GeneralForm extends Form {
    protected string $title,$errTitle,$errMsg;
    abstract protected function initialize(): void;
    
    public function __construct($post) {
        $this->errTitle = 'Een of meerdere velden zijn leeg';
        $this->errMsg = 'Veld is leeg!';
        parent::__construct($post);
    }

    protected function render(): void {
        if ($this->isValidated()) {
            HtmlBuilder::showTitle($this->title);
            return;
        }

        $this->openForm($this->formClass);
        $this->openLegend();
        !empty($this->emptyFields) ? HtmlBuilder::showTitle($this->errMsg) : HtmlBuilder::showTitle($this->formTitle);
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
            HtmlBuilder::showSpan($this->errMsg, $inputClass);
        }
        
        HtmlBuilder::newLine();
    }
}
?>