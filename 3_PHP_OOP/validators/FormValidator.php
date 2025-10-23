<?php
class FormValidator {
    private array $fields,$postData,$fieldMap = [],$emptyFields = [],$invalidFields = [];
    private bool $validated = false,$passwordsMatch = true;

    public function __construct(array $fields, ?array $postData = null) {
        $this->fields = $fields;
        $this->postData = $postData ?? $_POST;
    }

    public function validate(): void {
        if (!$this->validated) {
            $this->buildFieldMap();
            if (!empty($this->postData)) {
                $this->checkEmptyFields();
                $this->validateFieldTypes();
                $this->validatePasswordMatch();
            }
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
        foreach ($this->fieldMap as $label => $slugName) {
            $field = $this->findFieldByLabel($label);
            if ($field && $field['type'] !== 'hidden') {
                if (!isset($this->postData[$slugName]) || trim($this->postData[$slugName]) === '') {
                    $this->emptyFields[] = $slugName;
                }
            }
        }
    }

    private function validateFieldTypes(): void {
        foreach ($this->fieldMap as $label => $slugName) {
            $field = $this->findFieldByLabel($label);
            if ($field && isset($this->postData[$slugName]) && trim($this->postData[$slugName]) !== '') {
                $value = $this->postData[$slugName];
                $isValid = match($field['type']) {
                    'email' => filter_var($value, FILTER_VALIDATE_EMAIL) !== false,
                    'number' => is_numeric($value),
                    default => true
                };
                
                if (!$isValid) {
                    $this->invalidFields[] = $slugName;
                }
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
        
        return !empty($this->postData) 
            && empty($this->emptyFields) 
            && empty($this->invalidFields) 
            && $this->passwordsMatch;
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

    public function getInvalidFields(): array {
        if (!$this->validated) {
            $this->validate();
        }
        return $this->invalidFields;
    }

    private function slugify(string $text): string {
        $text = strtolower(trim($text));
        $text = str_replace([' ', '-', '_'], '', $text);
        $text = preg_replace('/[^a-z0-9]/', '', $text);
        return $text;
    }
}