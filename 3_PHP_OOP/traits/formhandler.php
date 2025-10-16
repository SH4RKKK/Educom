<?php
trait FormHandler {
    protected Form $form;
    
    protected function renderForm(): void {
        $this->form->show();
    }
}
?>