<?php
class Database {
    private string $host,$dbname,$username,$password;
    private $conn;

    public function __construct($host, $dbname, $username, $password) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    private function connect() {
        try {
            if (!$this->conn) {
                $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->conn;
        } catch (Throwable $e) {
            throw new Exception('Failed to connect to database: ' . $e->getMessage());
        }
    }

    private function query($query, $params = []) {
        try {
            if (!$this->conn) {
                $this->connect();
            }
            $statement = $this->conn->prepare($query);
            $statement->execute($params);
            return $statement->fetchAll();
        } catch (Throwable $e) {
            throw new Exception('Failed to execute query: ' . $e->getMessage());
        }
    }

    public function fetchUser(string $email): array {
        $query = "SELECT id, name, password FROM users WHERE email = :email LIMIT 1";
        $params = ['email' => $email];
        $result = $this->query($query, $params);
        return $result[0] ?? [];
    }

    public function insertUser(string $name, string $email, string $password): void {
        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $params = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];
        $this->query($query, $params);
    }

    public function fetchItems(bool $includeDescription): array {
        $columns = 
            $includeDescription 
            ? "id, name, price, image_path, description" 
            : "id, name, price, image_path";
        
        $query = "SELECT $columns FROM items ORDER BY id ASC";
        $result = $this->query($query);
        return $result ?? [];
    }

    public function appendOrder(int $userId, array $cartItems): string {
        try {
            $this->conn->beginTransaction();
            
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['price'] * $item['amount'];
            }
            
            // Insert into orders table
            $query = "INSERT INTO orders (user_id, status_id, total) VALUES (:user_id, 1, :total)";
            $params = [
                'user_id' => $userId,
                'total' => $total
            ];
            $this->query($query, $params);
            
            $orderId = $this->conn->lastInsertId();
            
            // Insert into order_items table
            $query = "INSERT INTO order_items (order_id, item_id, amount, unit_price) VALUES (:order_id, :item_id, :amount, :unit_price)";
            foreach ($cartItems as $item) {
                $params = [
                    'order_id' => $orderId,
                    'item_id' => $item['id'],
                    'amount' => $item['amount'],
                    'unit_price' => $item['price']
                ];
                $this->query($query, $params);
            }
            
            $this->conn->commit();
            unset($_SESSION['orders']);
            
            return 'Order geplaatst! Order #' . $orderId;
            
        } catch (Throwable $e) {
            $this->conn->rollback();
            return 'Error adding order: ' . $e->getMessage();
        }
    }
}