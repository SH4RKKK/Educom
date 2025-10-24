<?php
require_once '../abstract/Model.php';
class UserModel extends Model {
    public function loginUser(string $email, string $password): array {
        try {
            $user = $this->fetchUser($email);
            
            if (empty($user)) {
                return [
                    'success' => false,
                    'message' => 'E-mail niet gevonden'
                ];
            }

            if (!password_verify($password, $user['password'])) {
                return [
                    'success' => false,
                    'message' => 'Incorrect wachtwoord'
                ];
            }

            return [
                'success' => true,
                'message' => 'Login succesvol',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to login: ' . $e->getMessage()
            ];
        }
    }

    public function registerUser(string $name, string $email, string $password): array {
        try {
            $user = $this->fetchUser($email);
            
            if (!empty($user)) {
                return [
                    'success' => false,
                    'message' => 'E-mail is al geregistreerd'
                ];
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->insertUser($name, $email, $hashedPassword);
            
            return [
                'success' => true,
                'message' => 'Registratie succesvol'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to register: ' . $e->getMessage()
            ];
        }
    }

    private function fetchUser(string $email): ?array {
        $query = "SELECT * FROM users WHERE email = :email";
        $params = ['email' => $email];
        $result = $this->database->query($query, $params);
        return !empty($result) ? $result[0] : null;
    }
    
    private function insertUser(string $name, string $email, string $hashedPassword): void {
        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $params = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ];
        $this->database->query($query, $params);
    }
}