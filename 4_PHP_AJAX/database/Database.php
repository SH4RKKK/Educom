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

    private function connect(): void {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Throwable $e) {
            throw new Exception('Failed to connect to database: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO {
        if (!$this->conn) $this->connect();
        return $this->conn;
    }

    public function query($query, $params = []): array {
        try {
            if (!$this->conn) $this->connect();
            $statement = $this->conn->prepare($query);
            $statement->execute($params);
            return $statement->fetchAll();
        } catch (Throwable $e) {
            throw new Exception('Failed to execute query: ' . $e->getMessage());
        }
    }
}