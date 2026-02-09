<?php
require_once '../controllers/AdminController.php';

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$controller = new AdminController();

// Get request method
$method = $_SERVER['REQUEST_METHOD'];
$response = null;

// Handle API request
switch ($method) {
    case 'POST':
        // Login
        $data = json_decode(file_get_contents('php://input'), true);
        if(empty($data)) {
            $data = $_POST;
        }
        
        if((isset($data['action']) && $data['action'] === 'login') || (isset($data['email']) && isset($data['password']))) {
            $response = $controller->login($data);
        } else if(isset($data['action']) && $data['action'] === 'logout') {
            $response = $controller->logout();
        } else {
            $response = ['success' => false, 'message' => 'Invalid action'];
            http_response_code(400);
        }
        break;
        
    case 'GET':
        // Get profile, interviewers, single interviewer, or download resume
        if(isset($_GET['action'])) {
            switch($_GET['action']) {
                case 'profile':
                    $response = $controller->getProfile();
                    break;
                    
                case 'interviewers':
                    if(isset($_GET['id'])) {
                        $response = $controller->getSingleInterviewer($_GET['id']);
                    } else {
                        $response = $controller->getAllInterviewers();
                    }
                    break;
                    
                case 'download-resume':
                    if(isset($_GET['resume'])) {
                        $response = $controller->downloadResume($_GET['resume']);
                    } else {
                        $response = ['success' => false, 'message' => 'Resume parameter is required'];
                        http_response_code(400);
                    }
                    break;
                    
                default:
                    $response = ['success' => false, 'message' => 'Invalid action'];
                    http_response_code(400);
                    break;
            }
        } else {
            $response = ['success' => false, 'message' => 'Action parameter is required'];
            http_response_code(400);
        }
        break;
        
    case 'DELETE':
        // Delete interviewer
        if(isset($_GET['action']) && $_GET['action'] === 'delete-interviewer' && isset($_GET['id'])) {
            $response = $controller->deleteInterviewer($_GET['id']);
        } else {
            $response = ['success' => false, 'message' => 'Invalid action or missing parameters'];
            http_response_code(400);
        }
        break;
        
    default:
        // Invalid method
        $response = ['success' => false, 'message' => 'Method not allowed'];
        http_response_code(405);
        break;
}

if ($response !== null && !headers_sent()) {
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>