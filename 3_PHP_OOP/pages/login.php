<?php
require_once '../base/bodycontent.php';
require_once '../forms/generalform.php';

class Login extends BodyContent {
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
?>