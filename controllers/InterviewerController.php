<?php
// Determine the correct path to include files
$currentDir = dirname(__FILE__);
require_once $currentDir . '/../config/Database.php';
require_once $currentDir . '/../models/Interviewer.php';

class InterviewerController {
    private $database;
    private $db;
    private $interviewer;

    public function __construct() {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->interviewer = new Interviewer($this->db);
    }

    // Create interviewer
    public function createInterviewer($data) {
        // Validate input fields
        $errors = [];

        if(empty($data['name'])) {
            $errors['name'] = 'Interviewer name is required';
        }

        if(empty($data['phone'])) {
            $errors['phone'] = 'Phone number is required';
        } elseif(!preg_match('/^\d{10,15}$/', $data['phone'])) {
            $errors['phone'] = 'Phone number must be 10-15 digits';
        }

        if(empty($data['email'])) {
            $errors['email'] = 'Email address is required';
        } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        if(empty($data['address'])) {
            $errors['address'] = 'Address is required';
        }

        if(empty($data['qualification'])) {
            $errors['qualification'] = 'Qualification is required';
        }

        if(empty($data['experience'])) {
            $errors['experience'] = 'Years of experience is required';
        } else {
            // Remove any non-numeric characters (like "year" suffix)
            $cleanExperience = preg_replace('/[^0-9.]/', '', $data['experience']);
            if(!is_numeric($cleanExperience) || $cleanExperience < 0) {
                $errors['experience'] = 'Years of experience must be a non-negative number';
            } else {
                $data['experience'] = $cleanExperience;
            }
        }

        // Handle resume - accept both file upload and URL
        if(empty($_FILES['resume']) || $_FILES['resume']['error'] == 4) {
            if(empty($data['resume'])) {
                $errors['resume'] = 'Resume is required (either file upload or URL)';
            }
        }

        if(!empty($errors)) {
            return $this->sendResponse(false, 'Validation failed', ['errors' => $errors]);
        }

        // Check if email exists within last 3 months
        $this->interviewer->email = $data['email'];
        if($this->interviewer->checkEmailExists()) {
            return $this->sendResponse(false, 'You have already registered within the last 3 months');
        }

        // Handle resume - accept both file upload and URL
        $resumePath = null;
        if(isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            // Handle file upload
            $resumePath = $this->uploadResume($_FILES['resume']);
            if($resumePath === false) {
                return $this->sendResponse(false, 'Resume upload failed');
            }
        } elseif(isset($data['resume'])) {
            // Handle resume URL
            $resumePath = $data['resume'];
        }

        // Set interviewer data
        $this->interviewer->name = $data['name'];
        $this->interviewer->phone = $data['phone'];
        $this->interviewer->email = $data['email'];
        $this->interviewer->address = $data['address'];
        $this->interviewer->qualification = $data['qualification'];
        $this->interviewer->experience = $data['experience'];
        $this->interviewer->resume = $resumePath;

        // Create interviewer
        if($this->interviewer->create()) {
            return $this->sendResponse(true, 'Interviewer created successfully', ['id' => $this->interviewer->id]);
        } else {
            // Delete uploaded resume if creation fails
            if($resumePath && file_exists('../uploads/' . $resumePath)) {
                unlink('../uploads/' . $resumePath);
            }
            return $this->sendResponse(false, 'Failed to create interviewer');
        }
    }

    // Upload resume file
    private function uploadResume($file) {
        $targetDir = '../uploads/';
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        // Allow certain file formats
        $allowedTypes = ['pdf', 'doc', 'docx'];
        if(!in_array($fileType, $allowedTypes)) {
            return false;
        }

        // Check file size (max 5MB)
        if($file['size'] > 5 * 1024 * 1024) {
            return false;
        }

        // Move uploaded file to target directory
        if(move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }

        return false;
    }

    // Get all interviewers
    public function getAllInterviewers() {
        $stmt = $this->interviewer->getAll();
        $interviewers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->sendResponse(true, 'Interviewers retrieved successfully', $interviewers);
    }

    // Get single interviewer
    public function getSingleInterviewer($id) {
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

    // Update interviewer
    public function updateInterviewer($id, $data) {
        $this->interviewer->id = $id;
        
        if(!$this->interviewer->getSingle()) {
            return $this->sendResponse(false, 'Interviewer not found');
        }

        // Validate input
        if(empty($data['name']) || empty($data['phone']) || empty($data['email']) || empty($data['address']) || empty($data['qualification']) || empty($data['experience'])) {
            return $this->sendResponse(false, 'All required fields must be filled');
        }

        // Validate email format
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->sendResponse(false, 'Invalid email format');
        }

        // Validate phone number
        if(!preg_match('/^\d{10,15}$/', $data['phone'])) {
            return $this->sendResponse(false, 'Phone number must be 10-15 digits');
        }

        // Handle resume upload if provided
        $resumePath = $this->interviewer->resume;
        if(isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
            $newResumePath = $this->uploadResume($_FILES['resume']);
            if($newResumePath !== false) {
                // Delete old resume
                if($resumePath && file_exists('../uploads/' . $resumePath)) {
                    unlink('../uploads/' . $resumePath);
                }
                $resumePath = $newResumePath;
            }
        }

        // Set interviewer data
        $this->interviewer->name = $data['name'];
        $this->interviewer->phone = $data['phone'];
        $this->interviewer->email = $data['email'];
        $this->interviewer->address = $data['address'];
        $this->interviewer->qualification = $data['qualification'];
        $this->interviewer->experience = $data['experience'];
        $this->interviewer->resume = $resumePath;

        // Update interviewer
        if($this->interviewer->update()) {
            return $this->sendResponse(true, 'Interviewer updated successfully');
        } else {
            return $this->sendResponse(false, 'Failed to update interviewer');
        }
    }

    // Delete interviewer
    public function deleteInterviewer($id) {
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