<?php
require_once '../abstract/Form.php';
class CardForm extends Form {
    private int $itemId;

    public function __construct(int $itemId) {
        $this->itemId = $itemId;
        parent::__construct();
    }

    protected function initialize(): void {
        $this->fields = [
            ['label' => 'amount', 'type' => 'number', 'value' => '1'],
            ['label' => 'page', 'type' => 'hidden', 'value' => 'order'],
            ['label' => 'item_id', 'type' => 'hidden', 'value' => $this->itemId]
        ];
        $this->setPostButton('Bestel Nu!');
    }

    protected function render(): void {
        $this->openForm();
        $this->renderFields();
        $this->renderPostButton();
        $this->closeForm();
    }

    protected function renderField(array $field): void {
        $this->renderInput($this->fieldMap['label'] ?? '', $field['type'] ?? 'text',$field['value'] ?? '');   
    }
}