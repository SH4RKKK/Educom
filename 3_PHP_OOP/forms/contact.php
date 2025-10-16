<?php
require_once '../abstract/Form.php';

class ContactForm extends Form {
    
    protected function initialize(): void {
        $this->formClass = 'myForm';
        $this->formTitle = $this->getFormTitle(); //Logic needs to be fixed later
        $this->fields = ['Naam', 'E-mail', 'Bericht'];
        $this->hiddenFields = ['page' => 'contact'];
        $this->buttonText = 'Verstuur';
        $this->buttonClass = '';
    }
    
    private function getFormTitle(): string {
        if (!empty($this->emptyFields)) {
            return 'Een of meerdere velden zijn leeg';
        }
        return 'Vul gegevens in om in contact te komen!!!';
    }
    
    /*
            $msg = $response['message'] ?: (!empty($response['empty']) 
                ? 'Een of meerdere velden zijn leeg'
                : 'Vul gegevens in om in contact te komen!!!');
                
    */

    protected function renderFields(): void {

    }
}
?>