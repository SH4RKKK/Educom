<?php
require_once '../base/body.php';
require_once '../forms/contactform.php';

class Contact extends BodyContent {
    private ContactForm $form;
    
    protected function initialize(): void {
        parent::initialize();

        $this->form = new ContactForm($_POST);
    }
    
    protected function render(): void {
        parent::render();
        $this->form->renderForm();
    }
}
?>