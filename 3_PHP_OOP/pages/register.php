<?php
require_once '../base/body.php';
require_once '../forms/registerform.php';

class Register extends BodyContent {
    private RegisterForm $form;

    protected function initialize(): void {
        parent::initialize();
        
        $this->form = new RegisterForm($_POST);
    }
    
    protected function render(): void {
        parent::render();
        $this->form->renderForm();
    }
}
?>