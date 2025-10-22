<?php
trait ValidateForm {
    public function validateForm(): bool {
        return $this->form->isFormValid();
    }

    public function failForm(string $errorMessage) {
        $this->form->setErrorMessage($errorMessage);
    }
}