<?php
require_once '../abstract/Model.php';
class UserModel extends Model {
    private string $error = '';
    
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

    public function loginUser(string $email, string $password): void {
        $user = $this->fetchUser($email);
        if (empty($user)) {
            $this->error = 'E-mail niet gevonden';
            return;
        }

        if (!password_verify($password, $user['password'])) {
            $this->error = 'Incorrect wachtwoord';
            return;
        }

        $_SESSION['username'] = $user['name'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['logged_in'] = true;
    }

    public function registerUser(string $name, string $email, string $password): void {
        $user = $this->fetchUser($email);
        if (!empty($user)) {
            $this->error = 'E-mail is al geregistreerd';
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->insertUser($name, $email, $hashedPassword);
    }

    public function getError(): string {
        return $this->error;
    }

}