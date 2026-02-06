<?php
require_once '../controllers/InterviewerController.php';

// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$controller = new InterviewerController();

// Get request method
$method = $_SERVER['REQUEST_METHOD'];
$response = null;

// Handle API request
switch ($method) {
    case 'POST':
        // Create interviewer - handle both form data and JSON
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            // Get JSON data from request body
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);
        } else {
            // Get form data
            $data = $_POST;
        }
        $response = $controller->createInterviewer($data);
        break;
        
    case 'GET':
        // Get all interviewers or single interviewer
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $response = $controller->getSingleInterviewer($id);
        } else {
            $response = $controller->getAllInterviewers();
        }
        break;
        
    case 'PUT':
        // Update interviewer
        parse_str(file_get_contents("php://input"), $putData);
        $_PUT = $putData;
        
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $response = $controller->updateInterviewer($id, $_PUT);
        } else {
            $response = ['success' => false, 'message' => 'ID parameter is required'];
            http_response_code(400);
        }
        break;
        
    case 'DELETE':
        // Delete interviewer
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $response = $controller->deleteInterviewer($id);
        } else {
            $response = ['success' => false, 'message' => 'ID parameter is required'];
            http_response_code(400);
        }
        break;
        
    default:
        // Invalid method
        $response = ['success' => false, 'message' => 'Method not allowed'];
        http_response_code(405);
        break;
}

// Send JSON response if we have one
if ($response !== null && !headers_sent()) {
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>