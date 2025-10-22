<?php
trait ValidateForm {
    public function ValidateForm(): bool {
        return $this->form->isFormValid();
    }
}