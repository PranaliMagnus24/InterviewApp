<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .gradient-bg {
        // background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .card-custom {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        border-color: #667eea;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .error-message {
        background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .logo {
        font-size: 48px;
        color: #667eea;
    }
    </style>
</head>

<body class="gradient-bg">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom p-5">
                    <div class="text-center mb-4">
                        <div class="logo mb-2">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h2 class="text-primary">Admin Login</h2>
                        <p class="text-muted">Please login to manage interviewers</p>
                    </div>

                    <?php
                    // Handle login form submission
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        require_once '../controllers/AdminController.php';
                        
                        $controller = new AdminController();
                        
                        $data = [
                            'action' => 'login',
                            'email' => $_POST['email'],
                            'password' => $_POST['password']
                        ];
                        
                        // Call login method directly
                        $responseData = $controller->login($data);
                        
                        if ($responseData['success']) {
                            // Redirect to dashboard on successful login
                            header('Location: dashboard.php');
                            exit;
                        } else {
                            echo '<div class="error-message">';
                            echo '<i class="fas fa-exclamation-circle"></i> ' . $responseData['message'];
                            echo '</div>';
                        }
                    }
                    ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label" for="email">
                                <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required
                                placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">
                                <i class="fas fa-lock"></i> Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required
                                placeholder="Enter your password">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted">Not an admin? <a href="../index.php" class="text-primary">Register
                                here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>