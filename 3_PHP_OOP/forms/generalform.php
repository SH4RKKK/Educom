<?php
require_once '../abstract/Form.php';

class GeneralForm extends Form {
    protected string $title, $errTitle, $errMsg,$passwordMismatchMsg;

    public function __construct(array $post, string $formClass, string $formTitle, array $fields, string $successTitle, string $btnMsg) {
        $this->errTitle = 'Een of meerdere velden zijn leeg';
        $this->errMsg = 'Veld is leeg!';
        $this->passwordMismatchMsg = 'Wachtwoorden komen niet overeen!';
        $this->formClass = $formClass;
        $this->formTitle = $formTitle;
        $this->fields = $fields;
        $this->title = $successTitle;
        $this->setPostButton($btnMsg);

        parent::__construct($post);
    }

    protected function render(): void {
        if ($this->validator->isValid()) {
            HtmlBuilder::showTitle($this->title);
            return;
        }

        $emptyFields = $this->getEmptyFields();
        $title = $this->formTitle;
        
        if (!empty($emptyFields)) {
            $title = $this->errTitle;
        } else if (!empty($this->postData) && !$this->validator->doPasswordsMatch()) {
            $title = $this->passwordMismatchMsg;
        }

        $this->openForm($this->formClass);
        $this->openLegend();
        HtmlBuilder::showTitle($title);
        $this->closeLegend();

        $this->renderFields();
        $this->renderPostButton();
        $this->closeForm();
    }

    protected function renderField(array $field): void {
        $label = $field['label'];
        $type = $field['type'] ?? 'text';
        $fieldMap = $this->getFieldMap();
        $emptyFields = $this->getEmptyFields();
        $name = $fieldMap[$label];
        
        $isEmpty = in_array($name, $emptyFields);
        $inputClass = $isEmpty ? 'error' : '';
        $value = !empty($this->postData[$name]) ? $this->postData[$name] : ($field['value'] ?? '');
        
        if($field['type'] !== 'hidden') $this->makeLabel($name, $label);
        $this->renderInput($name, $type, $value, $inputClass);
        
        if ($isEmpty) {
            HtmlBuilder::showSpan($this->errMsg, $inputClass);
        }
        
        HtmlBuilder::newLine();
    }

    public function isFormValid(): bool {
        return $this->validator->isValid();
    }
}