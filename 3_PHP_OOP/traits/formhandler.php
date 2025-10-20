<?php
trait FormHandler {
    protected ?Form $form = null; 
    
    protected function renderForm(): void {
        $this->form->renderForm();
    }
}
?>