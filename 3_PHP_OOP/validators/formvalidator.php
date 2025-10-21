<?php
class FormValidator {
    private array $fields,$postData,$fieldMap = [],$emptyFields = [];
    private bool $validated = false;
    private bool $passwordsMatch = true;

    public function __construct(array $fields, array $postData) {
        $this->fields = $fields;
        $this->postData = $postData;
    }

    public function validate(): void {
        if (!$this->validated) {
            $this->buildFieldMap();
            $this->checkEmptyFields();
            $this->validatePasswordMatch();
            $this->validated = true;
        }
    }

    private function buildFieldMap(): void {
        foreach ($this->fields as $field) {
            $label = $field['label'];
            $this->fieldMap[$label] = $this->slugify($label);
        }
    }

    private function checkEmptyFields(): void {
        foreach ($this->postData as $key => $value) {
            if (trim($value) === '') {
                $this->emptyFields[] = $key;
            }
        }
    }

    private function validatePasswordMatch(): void {
        $passwordFields = [];
        foreach ($this->fieldMap as $label => $slugName) {
            $field = $this->findFieldByLabel($label);
            if ($field && $field['type'] === 'password') {
                $passwordFields[] = $slugName;
            }
        }

        if (count($passwordFields) === 2) {
            $password1 = $this->postData[$passwordFields[0]] ?? '';
            $password2 = $this->postData[$passwordFields[1]] ?? '';
            $this->passwordsMatch = ($password1 === $password2);
        }
    }

    private function findFieldByLabel(string $label): ?array {
        foreach ($this->fields as $field) {
            if ($field['label'] === $label) {
                return $field;
            }
        }
        return null;
    }

    public function isValid(): bool {
        if (!$this->validated) {
            $this->validate();
        }
        return empty($this->emptyFields) && !empty($this->postData) && $this->passwordsMatch;
    }

    public function doPasswordsMatch(): bool {
        if (!$this->validated) {
            $this->validate();
        }
        return $this->passwordsMatch;
    }

    public function getEmptyFields(): array {
        if (!$this->validated) {
            $this->validate();
        }
        return $this->emptyFields;
    }

    public function getFieldMap(): array {
        if (!$this->validated) {
            $this->validate();
        }
        return $this->fieldMap;
    }

    public function getSlugifiedName(string $label): string {
        if (!$this->validated) {
            $this->validate();
        }
        return $this->fieldMap[$label] ?? $this->slugify($label);
    }

    private function slugify(string $text): string {
        $text = strtolower(trim($text));
        $text = str_replace([' ', '-', '_'], '', $text);
        $text = preg_replace('/[^a-z0-9]/', '', $text);
        return $text;
    }
}
?>