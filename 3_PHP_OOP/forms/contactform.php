<?php
require_once '../abstract/GeneralForm.php';

class ContactForm extends GeneralForm {
    protected function initialize(): void {
        $this->formClass = 'myForm';
        $this->formTitle = 'Vul gegevens in om in contact te komen!!!';
        $this->fields = [
            ['label' => 'Naam', 'type' => 'text'],
            ['label' => 'E-mail', 'type' => 'email'],
            ['label' => 'Bericht', 'type' => 'textarea'],
            ['label' => 'page', 'type' => 'hidden', 'value' => 'contact']
        ];
        $this->title = [
            'text' => 'Bedankt voor jouw bericht, we reageren zo snel mogelijk!',
            'class' => 'title'
        ];
        $this->addButton('Vestuur');
    }
}
?>