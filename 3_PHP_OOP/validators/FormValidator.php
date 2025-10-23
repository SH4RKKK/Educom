<?php
class FormValidator {
    private array $fields,$postData,$errors = [];
    private bool $validated = false;

    public function __construct(array $fields, ?array $postData = null) {
        $this->fields = $fields;
        $this->postData = $postData ?? $_POST;
    }

    public function validate(): void {
        if ($this->validated || empty($this->postData)) return;
        
        $this->validateFields();
        $this->validatePasswordMatch();
        $this->validated = true;
    }

    private function validateFields(): void {
        foreach ($this->fields as $field) {
            if ($field['type'] === 'hidden') continue;
            
            $name = self::slugify($field['label']);
            $value = trim($this->postData[$name] ?? '');
            
            if ($value === '') {
                $this->errors[$name] = 'empty';
                continue;
            }
            
            if (!$this->isValidType($field['type'], $value)) {
                $this->errors[$name] = 'invalid';
            }
        }
    }

    private function validatePasswordMatch(): void {
        $passwords = array_filter($this->fields, fn($f) => $f['type'] === 'password');
        if (count($passwords) !== 2) return;

        $values = array_map(fn($f) => $this->postData[self::slugify($f['label'])] ?? '', $passwords);
        if ($values[0] !== $values[1]) $this->errors['password_match'] = 'mismatch';
    }

    private function isValidType(string $type, string $value): bool {
        return match($type) {
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) !== false,
            'number' => is_numeric($value),
            default => true
        };
    }

    public final function isValid(): bool {
        if (!$this->validated) $this->validate();
        return empty($this->errors);
    }

    public function getErrors(): array {
        if (!$this->validated) $this->validate();
        return $this->errors;
    }

    public final function doPasswordsMatch(): bool {
        if (!$this->validated) $this->validate();
        return !isset($this->errors['password_match']);
    }

    public function getEmptyFields(): array {
        return array_keys(array_filter($this->errors, fn($e) => $e === 'empty'));
    }

    public function getInvalidFields(): array {
        return array_keys(array_filter($this->errors, fn($e) => $e === 'invalid'));
    }

    public static function slugify(string $text): string {
        $text = strtolower(trim($text));
        $text = str_replace([' ', '-', '_'], '', $text);
        $text = preg_replace('/[^a-z0-9]/', '', $text);
        return $text;
    }
}