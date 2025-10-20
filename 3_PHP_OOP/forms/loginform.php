<?php
require_once '../abstract/GeneralForm.php';

class LoginForm extends GeneralForm {
    protected function initialize(): void {
        $this->formClass = 'myForm';
        $this->formTitle = 'Welkom terug';
        $this->fields = [
            ['label' => 'E-mail', 'type' => 'email'],
            ['label' => 'Wachtwoord', 'type' => 'password'],
            ['label' => 'page', 'type' => 'hidden', 'value' => 'login']
        ];
        $this->title = [
            'text' => 'Login succesvol',
            'class' => 'title'
        ];
        $this->addButton('Login');
    }
}
?>