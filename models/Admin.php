<?php
class Admin {
    private $conn;
    private $table_name = "admin";

    public $id;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Admin login
    public function login() {
        $query = "SELECT id, email, password FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }

    // Get admin details
    public function getAdmin() {
        $query = "SELECT id, email FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id = $row['id'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }
}
?>