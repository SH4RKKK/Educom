<?php
trait ValidateForm {
    public function validateForm(): bool {
        return $this->form->isFormValid();
    }

    public function invalidateForm(string $errorMessage) {
        $this->form->failForm($errorMessage);
    }
}