<?php
require_once '../controllers/AdminController.php';

$controller = new AdminController();
$response = $controller->logout();

// Redirect to login page
header('Location: login.php');
exit;
?>