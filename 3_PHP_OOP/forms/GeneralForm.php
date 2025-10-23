<?php
require_once '../abstract/Form.php';

class GeneralForm extends Form {
    protected string $title, $errTitleEmpty, $errMsg, $passwordMismatchMsg, $invalidFieldMsg,$invalidMsg;
    protected ?string $errTitleFail = null;
    protected array $emptyFields = [], $invalidFields = [];

    public function __construct(string $formClass, string $formTitle, array $fields, string $successTitle, string $btnMsg) {
        $this->errTitleEmpty = 'Een of meerdere velden zijn leeg';
        $this->errMsg = 'Veld is leeg!';
        $this->invalidMsg = 'Ongeldige waarde';
        $this->passwordMismatchMsg = 'Wachtwoorden komen niet overeen!';
        $this->invalidFieldMsg = 'Ongeldige veldwaarden!';
        $this->formClass = $formClass;
        $this->formTitle = $formTitle;
        $this->fields = $fields;
        $this->title = $successTitle;
        $this->setPostButton($btnMsg);
        parent::__construct();
    }

    protected function renderForm(): void {
        if ($this->isFormValid() && !empty($this->postData)) {
            HtmlBuilder::showTitle($this->title);
            return;
        }

        $this->emptyFields = $this->getEmptyFields();
        $this->invalidFields = $this->getInvalidFields();

        $title = $this->formTitle;
        if (isset($this->errTitleFail)) {
            $title = $this->errTitleFail;
        } else if (!empty($this->invalidFields)) {
            $title = $this->invalidFieldMsg;
        } else if (!$this->validator->doPasswordsMatch()) {
            $title = $this->passwordMismatchMsg;
        } else if (!empty($this->emptyFields)) {
            $title = $this->errTitleEmpty;
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
        $name = FormValidator::slugify($label);
        
        $isEmpty = in_array($name, $this->emptyFields);
        $isInvalid = in_array($name, $this->invalidFields);
        $inputClass = ($isEmpty || $isInvalid) ? 'error' : '';
        $value = !empty($this->postData[$name]) ? $this->postData[$name] : ($field['value'] ?? '');
        
        if ($field['type'] !== 'hidden') $this->makeLabel($name, $label);
        $this->renderInput($name, $type, $value, $inputClass);
        
        if ($isEmpty) {
            HtmlBuilder::showSpan($this->errMsg, $inputClass);
        } else if ($isInvalid) {
            HtmlBuilder::showSpan($this->invalidMsg, $inputClass);
        }
        
        HtmlBuilder::newLine();
    }

    public function isFormValid(): bool {
        return $this->validator->isValid();
    }

    public function failForm(string $message): void {
        $this->errTitleFail = $message;
        $this->setFormToFail();
    }

    private function setFormToFail(): void {
        $this->validator = new FormValidator($this->fields, []);
        $this->validator->validate();
    }
}