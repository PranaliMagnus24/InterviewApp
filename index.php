<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .card-custom {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        max-height: 80vh;
        overflow-y: auto;
    }

    .card-custom::-webkit-scrollbar {
        width: 8px;
    }

    .card-custom::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .card-custom::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .card-custom::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
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

    .success-message {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .error-message {
        background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    </style>
</head>

<body class="gradient-bg">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="card card-custom p-5">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">
                            <i class="fas fa-user-tie"></i> Interview Registration
                        </h2>
                        <p class="text-muted">Please fill in your details to register for the interview</p>
                    </div>

                    <?php
                    // Handle form submission
                    $errors = [];
                    $successMessage = '';
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        require_once 'controllers/InterviewerController.php';
                        
                        $controller = new InterviewerController();
                        
                        // Create form data array
                        $formData = [
                            'name' => $_POST['name'],
                            'phone' => $_POST['phone'],
                            'email' => $_POST['email'],
                            'address' => $_POST['address'],
                            'qualification' => $_POST['qualification'],
                            'experience' => $_POST['experience']
                        ];
                        
                        // Call createInterviewer method and get response
                        $responseData = $controller->createInterviewer($formData);
                        
                        // Display message
                        if ($responseData['success']) {
                            $successMessage = $responseData['message'];
                            // Clear form fields
                            echo '<script>document.getElementById("registrationForm").reset();</script>';
                        } else {
                            if (isset($responseData['data']['errors'])) {
                                $errors = $responseData['data']['errors'];
                            } else {
                                $errors['general'] = $responseData['message'];
                            }
                        }
                    }
                    ?>

                    <?php if (!empty($successMessage)): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> <?php echo $successMessage; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($errors['general'])): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $errors['general']; ?>
                    </div>
                    <?php endif; ?>

                    <form id="registrationForm" method="POST" enctype="multipart/form-data" novalidate
                        onsubmit="console.log('Form submitted'); return true;">
                        <div class="mb-3">
                            <label class="form-label" for="name">
                                <i class="fas fa-user"></i> Interviewer Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name"
                                name="name" placeholder="Enter your full name"
                                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $errors['name']; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">
                                <i class="fas fa-phone"></i> Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel"
                                class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>"
                                id="phone" name="phone" placeholder="Enter your phone number"
                                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            <?php if (isset($errors['phone'])): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $errors['phone']; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">
                                <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                id="email" name="email" placeholder="Enter your email address"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $errors['email']; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="address">
                                <i class="fas fa-map-marker-alt"></i> Address <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control <?php echo isset($errors['address']) ? 'is-invalid' : ''; ?>"
                                id="address" name="address" rows="3"
                                placeholder="Enter your address"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                            <?php if (isset($errors['address'])): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $errors['address']; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="qualification">
                                <i class="fas fa-graduation-cap"></i> Qualification <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-control <?php echo isset($errors['qualification']) ? 'is-invalid' : ''; ?>"
                                id="qualification" name="qualification">
                                <option value="">Select your qualification</option>
                                <option value="High School"
                                    <?php echo (isset($_POST['qualification']) && $_POST['qualification'] == 'High School') ? 'selected' : ''; ?>>
                                    High School</option>
                                <option value="Bachelor's"
                                    <?php echo (isset($_POST['qualification']) && $_POST['qualification'] == 'Bachelor\'s') ? 'selected' : ''; ?>>
                                    Bachelor's Degree</option>
                                <option value="Master's"
                                    <?php echo (isset($_POST['qualification']) && $_POST['qualification'] == 'Master\'s') ? 'selected' : ''; ?>>
                                    Master's Degree</option>
                                <option value="PhD"
                                    <?php echo (isset($_POST['qualification']) && $_POST['qualification'] == 'PhD') ? 'selected' : ''; ?>>
                                    PhD</option>
                                <option value="Diploma"
                                    <?php echo (isset($_POST['qualification']) && $_POST['qualification'] == 'Diploma') ? 'selected' : ''; ?>>
                                    Diploma</option>
                                <option value="Other"
                                    <?php echo (isset($_POST['qualification']) && $_POST['qualification'] == 'Other') ? 'selected' : ''; ?>>
                                    Other</option>
                            </select>
                            <?php if (isset($errors['qualification'])): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $errors['qualification']; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="experience">
                                <i class="fas fa-briefcase"></i> Years of Experience <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control <?php echo isset($errors['experience']) ? 'is-invalid' : ''; ?>"
                                id="experience" name="experience" placeholder="Enter years of experience" min="0"
                                value="<?php echo isset($_POST['experience']) ? htmlspecialchars($_POST['experience']) : ''; ?>">
                            <?php if (isset($errors['experience'])): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $errors['experience']; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="resume">
                                <i class="fas fa-file-pdf"></i> Resume Upload <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                class="form-control <?php echo isset($errors['resume']) ? 'is-invalid' : ''; ?>"
                                id="resume" name="resume" accept=".pdf,.doc,.docx">
                            <div class="form-text">Allowed formats: PDF, DOC, DOCX (Max 5MB)</div>
                            <?php if (isset($errors['resume'])): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $errors['resume']; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"
                                onclick="console.log('Submit button clicked'); return true;">
                                <i class="fas fa-paper-plane"></i> Submit Registration
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted">Admin? <a href="admin/login.php" class="text-primary">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>