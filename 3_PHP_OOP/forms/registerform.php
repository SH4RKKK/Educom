<?php
require_once '../abstract/GeneralForm.php';
require_once '../utility/stringhelper.php';

class RegisterForm extends GeneralForm {
    private string $passwordMisMatchErrMsg = 'Wachtwoorden komen niet overeen';

    protected function initialize(): void {
        $this->formClass = 'myForm';
        $this->formTitle = 'Vul gegevens onderin aan om te registeren';
        $this->fields = [
            ['label' => 'Naam', 'type' => 'text'],
            ['label' => 'E-mail', 'type' => 'email'],
            ['label' => 'Wachtwoord', 'type' => 'password'],
            ['label' => 'Herhaal Wachtwoord', 'type' => 'password']
        ];
        $this->hiddenFields = ['page' => 'register'];
        $this->title = [
            'text' => 'Succesvol geregisteerd!!!',
            'class' => 'title'
        ];
        $this->addButton('Registreer');
    }

    protected function isValidated(): bool {
        if (!parent::isValidated()) return false;
        
        if (!$this->passwordsMatch()) {
            $this->formTitle = $this->passwordMisMatchErrMsg;
            return false;
        }
        
        return true;
    }

    private function passwordsMatch(): bool {  
        return ($this->postData['wachtwoord'] ?? '') === ($this->postData['herhaalwachtwoord'] ?? '');
    }
}
?>