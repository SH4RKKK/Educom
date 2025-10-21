<?php
require_once '../base/bodycontent.php';
require_once '../forms/generalform.php';

class Contact extends BodyContent {
    private GeneralForm $form;
    
    protected function initialize(): void {
        parent::initialize();

        $this->form = new GeneralForm(
            $_POST,
            'myForm',
            'Vul gegevens in om in contact te komen!!!',
            [
                ['label' => 'Naam', 'type' => 'text'],
                ['label' => 'E-mail', 'type' => 'email'],
                ['label' => 'Bericht', 'type' => 'textarea'],
                ['label' => 'page', 'type' => 'hidden', 'value' => 'contact']
            ],
            'Bedankt voor jouw bericht, we reageren zo snel mogelijk!',
            'Vestuur'
        );
    }
    
    protected function render(): void {
        parent::render();
        $this->form->renderForm();
    }

    public function isFormValid(): bool {
        return $this->form->isFormValid();
    }

    public function getValidatedData(): array {
        return $this->form->getValidatedData();
    }
}
?>