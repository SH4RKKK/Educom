<?php
require_once '../abstract/GeneralForm.php';

class RegisterForm extends GeneralForm {
    private string $passwordMisMatchErrMsg = 'Wachtwoorden komen niet overeen';

    protected function initialize(): void {
        $this->formClass = 'myForm';
        $this->formTitle = 'Vul gegevens onderin aan om te registeren';
        $this->fields = [
            ['label' => 'Naam', 'type' => 'text'],
            ['label' => 'E-mail', 'type' => 'email'],
            ['label' => 'Wachtwoord', 'type' => 'password'],
            ['label' => 'Herhaal Wachtwoord', 'type' => 'password'],
            ['label' => 'page', 'type' => 'hidden', 'value' => 'register']
        ];
        $this->title = 'Succesvol geregisteerd!!!';
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