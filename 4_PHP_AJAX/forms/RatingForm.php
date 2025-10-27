<?php
require_once '../abstract/Form.php';

class RatingForm extends Form {
    private int $itemId;
    private string $ratingBtnClass;

    public function __construct(int $itemId) {
        $this->itemId = $itemId;
        $this->initialize();
        parent::__construct();
    }

    protected function initialize(): void {
        $this->fields = [
            ['label' => 'item_id', 'type' => 'hidden', 'value' => $this->itemId],
            ['label' => 'page', 'type' => 'hidden', 'value' => 'rating']
        ];

        if (!empty($_GET['id'])) {
            $this->fields[] = ['label' => 'id', 'type' => 'hidden', 'value' => $_GET['id']];
        }

        $this->ratingBtnClass = 'rating-btn';
    }

    protected function renderForm(): void {
        $this->openForm();
        $this->renderFields();
        $this->renderRatingButtons();
        $this->closeForm();
    }

    protected function renderField(array $field): void {
        $this->renderInput($field['label'] ?? '', $field['type'] ?? 'text', $field['value'] ?? '');   
    }

    private function renderRatingButtons(): void {
        for ($i = 1; $i <= 5; $i++) {
            $this->setPostButton($i . ' ⭐', 'rating', (string)$i, $this->ratingBtnClass);
            $this->renderPostButton();
        }
    }
}