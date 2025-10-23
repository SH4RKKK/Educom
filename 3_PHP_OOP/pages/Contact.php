<?php
require_once '../base/BodyContent.php';
require_once '../forms/GeneralForm.php';
require_once '../traits/ValidateForm.php';

class Contact extends BodyContent {
    use ValidateForm;
    private GeneralForm $form;
    
    protected function initialize(): void {
        parent::initialize();

        $this->form = new GeneralForm(
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
}