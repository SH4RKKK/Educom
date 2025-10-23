<?php
require_once '../base/BodyContent.php';
require_once '../forms/GeneralForm.php';
require_once '../traits/ValidateForm.php';

class Register extends BodyContent {
    use ValidateForm;
    private GeneralForm $form;

    protected function initialize(): void {
        parent::initialize();
        $this->form = new GeneralForm(
            'myForm',
            'Vul gegevens onderin aan om te registeren',
            [
                ['label' => 'Naam', 'type' => 'text'],
                ['label' => 'E-mail', 'type' => 'email'],
                ['label' => 'Wachtwoord', 'type' => 'password'],
                ['label' => 'Herhaal Wachtwoord', 'type' => 'password'],
                ['label' => 'page', 'type' => 'hidden', 'value' => 'register']
            ],
            'Succesvol geregisteerd!!!',
            'Registreer'
        );
    }
    
    protected function renderBody(): void {
        parent::renderBody();
        $this->form->render();
    }
}