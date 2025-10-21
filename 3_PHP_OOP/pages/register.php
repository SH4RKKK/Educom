<?php
require_once '../base/bodycontent.php';
require_once '../forms/generalform.php';

class Register extends BodyContent {
    private GeneralForm $form;

    protected function initialize(): void {
        parent::initialize();
        
        $this->form = new GeneralForm(
            $_POST,
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
    
    protected function render(): void {
        parent::render();
        $this->form->renderForm();
    }
}
?>