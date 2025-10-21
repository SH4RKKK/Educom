<?php
require_once '../base/body.php';
require_once '../forms/loginform.php';

class Login extends BodyContent {
    private LoginForm $form;
    
    protected function initialize(): void {
        parent::initialize();

        $this->form = new LoginForm($_POST);
    }
    
    protected function render(): void {
        parent::render();
        $this->form->renderForm();
    }
}
?>