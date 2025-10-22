<?php
require_once '../base/BodyContent.php';
require_once '../forms/GeneralForm.php';
require_once '../traits/ValidateForm.php';

class Login extends BodyContent {
    use ValidateForm;
    private GeneralForm $form;
    
    protected function initialize(): void {
        parent::initialize();

        $this->form = new GeneralForm(
            $_POST,
            'myForm',
            'Welkom terug',
            [
                ['label' => 'E-mail', 'type' => 'email'],
                ['label' => 'Wachtwoord', 'type' => 'password'],
                ['label' => 'page', 'type' => 'hidden', 'value' => 'login']
            ],
            'Login succesvol',
            'Login'
        );
    }
    
    protected function render(): void {
        parent::render();
        $this->form->renderForm();
    }
}