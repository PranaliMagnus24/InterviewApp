<?php
class Interviewer {
    private $conn;
    private $table_name = "interviewers";

    public $id;
    public $name;
    public $phone;
    public $email;
    public $address;
    public $qualification;
    public $experience;
    public $resume;
    public $created_at;
    public $verified;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new interviewer
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, phone=:phone, email=:email, address=:address, qualification=:qualification, experience=:experience, resume=:resume, verified=0";
        
        $stmt = $this->conn->prepare($query);
        
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->qualification = htmlspecialchars(strip_tags($this->qualification));
        $this->experience = htmlspecialchars(strip_tags($this->experience));
        $this->resume = htmlspecialchars(strip_tags($this->resume));
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":qualification", $this->qualification);
        $stmt->bindParam(":experience", $this->experience);
        $stmt->bindParam(":resume", $this->resume);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Check if email exists within last 3 months
    public function checkEmailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Get all interviewers
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get single interviewer
    public function getSingle() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->address = $row['address'];
            $this->qualification = $row['qualification'];
            $this->experience = $row['experience'];
            $this->resume = $row['resume'];
            $this->created_at = $row['created_at'];
            $this->verified = $row['verified'];
            return true;
        }
        return false;
    }

    // Update interviewer
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name=:name, phone=:phone, email=:email, address=:address, qualification=:qualification, experience=:experience" . 
                (!empty($this->resume) ? ", resume=:resume" : "") . " WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->qualification = htmlspecialchars(strip_tags($this->qualification));
        $this->experience = htmlspecialchars(strip_tags($this->experience));
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":qualification", $this->qualification);
        $stmt->bindParam(":experience", $this->experience);
        $stmt->bindParam(":id", $this->id);
        
        if(!empty($this->resume)) {
            $this->resume = htmlspecialchars(strip_tags($this->resume));
            $stmt->bindParam(":resume", $this->resume);
        }
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete interviewer
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Verify interviewer
    public function verify() {
        $query = "UPDATE " . $this->table_name . " SET verified=1 WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>