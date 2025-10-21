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
        $this->addButton($btnMsg);

        parent::__construct($post);
    }

    protected function render(): void {
        if ($this->isValidated()) {
            HtmlBuilder::showTitle($this->title);
            return;
        }

        $emptyFields = $this->getEmptyFields();
        $title = $this->formTitle;
        
        if (!empty($this->postData) && !$this->validator->doPasswordsMatch()) {
            $title = $this->passwordMismatchMsg;
        } else if (!empty($emptyFields)) {
            $title = $this->errTitle;
        }

        $this->openForm($this->formClass);
        $this->openLegend();
        HtmlBuilder::showTitle($title);
        $this->closeLegend();

        $this->renderFields();
        $this->renderButtons();
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

    public function validate(): void {
        if ($this->validator === null) {
            $this->validator = new FormValidator($this->fields, $this->postData);
            $this->validator->validate();
        }
    }

    public function isFormValid(): bool {
        $this->validate();
        return $this->isValidated();
    }

    public function getValidatedData(): array {
        $this->validate();
        $fieldMap = $this->getFieldMap();
        $data = [];
        
        foreach ($this->fields as $field) {
            if ($field['type'] !== 'hidden') {
                $label = $field['label'];
                $slugName = $fieldMap[$label];
                $data[$slugName] = $this->postData[$slugName] ?? '';
            }
        }
        
        return $data;
    }
}
?>