<?php
require_once '../config/Database.php';
require_once '../models/Admin.php';
require_once '../models/Interviewer.php';

class AdminController {
    private $database;
    private $db;
    private $admin;
    private $interviewer;

    public function __construct() {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->admin = new Admin($this->db);
        $this->interviewer = new Interviewer($this->db);
    }

    // Admin login
    public function login($data) {
        if(empty($data['email']) || empty($data['password'])) {
            return $this->sendResponse(false, 'Email and password are required');
        }

        $this->admin->email = $data['email'];
        $this->admin->password = $data['password'];

        if($this->admin->login()) {
            // Start session and store admin data
            session_start();
            $_SESSION['admin_id'] = $this->admin->id;
            $_SESSION['admin_email'] = $this->admin->email;
            
            return $this->sendResponse(true, 'Login successful', ['id' => $this->admin->id, 'email' => $this->admin->email]);
        } else {
            return $this->sendResponse(false, 'Invalid email or password');
        }
    }

    // Admin logout
    public function logout() {
        session_start();
        session_destroy();
        return $this->sendResponse(true, 'Logout successful');
    }

    // Check if admin is logged in
    public function isLoggedIn() {
        session_start();
        if(isset($_SESSION['admin_id'])) {
            return true;
        }
        return false;
    }

    // Get admin profile
    public function getProfile() {
        if(!$this->isLoggedIn()) {
            return $this->sendResponse(false, 'Please login first');
        }

        $this->admin->id = $_SESSION['admin_id'];
        if($this->admin->getAdmin()) {
            return $this->sendResponse(true, 'Profile retrieved successfully', [
                'id' => $this->admin->id,
                'email' => $this->admin->email
            ]);
        } else {
            return $this->sendResponse(false, 'Admin not found');
        }
    }

    // Get all interviewers (admin)
    public function getAllInterviewers() {
        if(!$this->isLoggedIn()) {
            return $this->sendResponse(false, 'Please login first');
        }

        $stmt = $this->interviewer->getAll();
        $interviewers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->sendResponse(true, 'Interviewers retrieved successfully', $interviewers);
    }

    // Get single interviewer (admin)
    public function getSingleInterviewer($id) {
        if(!$this->isLoggedIn()) {
            return $this->sendResponse(false, 'Please login first');
        }

        $this->interviewer->id = $id;
        
        if($this->interviewer->getSingle()) {
            $interviewer = [
                'id' => $this->interviewer->id,
                'name' => $this->interviewer->name,
                'phone' => $this->interviewer->phone,
                'email' => $this->interviewer->email,
                'address' => $this->interviewer->address,
                'qualification' => $this->interviewer->qualification,
                'experience' => $this->interviewer->experience,
                'resume' => $this->interviewer->resume,
                'created_at' => $this->interviewer->created_at,
                'verified' => $this->interviewer->verified
            ];
            
            return $this->sendResponse(true, 'Interviewer retrieved successfully', $interviewer);
        } else {
            return $this->sendResponse(false, 'Interviewer not found');
        }
    }

    // Delete interviewer (admin)
    public function deleteInterviewer($id) {
        if(!$this->isLoggedIn()) {
            return $this->sendResponse(false, 'Please login first');
        }

        $this->interviewer->id = $id;
        
        if(!$this->interviewer->getSingle()) {
            return $this->sendResponse(false, 'Interviewer not found');
        }

        // Delete resume file
        if($this->interviewer->resume && file_exists('../uploads/' . $this->interviewer->resume)) {
            unlink('../uploads/' . $this->interviewer->resume);
        }

        // Delete interviewer
        if($this->interviewer->delete()) {
            return $this->sendResponse(true, 'Interviewer deleted successfully');
        } else {
            return $this->sendResponse(false, 'Failed to delete interviewer');
        }
    }

    // Download resume (admin)
    public function downloadResume($resumeName) {
        if(!$this->isLoggedIn()) {
            return $this->sendResponse(false, 'Please login first');
        }

        $resumePath = '../uploads/' . $resumeName;
        
        if(file_exists($resumePath)) {
            $fileType = mime_content_type($resumePath);
            $fileName = basename($resumePath);
            
            header('Content-Type: ' . $fileType);
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Length: ' . filesize($resumePath));
            
            readfile($resumePath);
            exit;
        } else {
            return $this->sendResponse(false, 'Resume not found');
        }
    }

    // Send JSON response
    private function sendResponse($success, $message, $data = null) {
        $response = [
            'success' => $success,
            'message' => $message
        ];
        
        if($data !== null) {
            $response['data'] = $data;
        }
        
        return $response;
    }
}
?>