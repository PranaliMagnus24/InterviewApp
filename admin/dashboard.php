<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .gradient-bg {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .sidebar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        color: white;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        display: block;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 5px;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background: rgba(255, 255, 255, 0.2);
    }

    .card-custom {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-5px);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .btn-danger {
        border-radius: 50px;
        padding: 5px 15px;
    }

    .btn-success {
        border-radius: 50px;
        padding: 5px 15px;
    }

    .btn-info {
        border-radius: 50px;
        padding: 5px 15px;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .status-verified {
        background-color: #d4edda;
        color: #155724;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    </style>
</head>

<body class="gradient-bg">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="p-3">
                    <h3 class="text-center mb-4">
                        <i class="fas fa-user-shield"></i> Admin Panel
                    </h3>
                    <nav>
                        <a href="dashboard.php" class="active">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="interviewers.php">
                            <i class="fas fa-users"></i> Interviewers
                        </a>
                        <a href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-primary">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </h2>
                    <div class="card-custom p-3 bg-white">
                        <h5>Welcome, Admin!</h5>
                        <p class="text-muted mb-0">Manage interview registration system</p>
                    </div>
                </div>

                <!-- Dashboard Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card-custom bg-primary text-white p-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-white mb-1">Total Interviewers</p>
                                    <h3 id="totalInterviewers">0</h3>
                                </div>
                                <div>
                                    <i class="fas fa-users fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card-custom bg-success text-white p-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-white mb-1">Verified</p>
                                    <h3 id="verifiedInterviewers">0</h3>
                                </div>
                                <div>
                                    <i class="fas fa-check-circle fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card-custom bg-warning text-white p-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-white mb-1">Pending</p>
                                    <h3 id="pendingInterviewers">0</h3>
                                </div>
                                <div>
                                    <i class="fas fa-clock fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card-custom bg-info text-white p-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-white mb-1">Resumes Uploaded</p>
                                    <h3 id="resumesUploaded">0</h3>
                                </div>
                                <div>
                                    <i class="fas fa-file-alt fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Interviewers Table -->
                <div class="card-custom bg-white p-4">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-list"></i> Recent Interviewers
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="interviewersTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Experience</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="interviewersTableBody">
                                <!-- Data will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="interviewers.php" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View All Interviewers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Viewing Interviewer Details -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">
                        <i class="fas fa-user-tie"></i> Interviewer Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewModalBody">
                    <!-- Interviewer details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Function to load dashboard stats
    function loadDashboardStats() {
        $.ajax({
            url: '../api/admin.php?action=interviewers',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const interviewers = response.data;
                    const total = interviewers.length;
                    const verified = interviewers.filter(interviewer => interviewer.verified === 1).length;
                    const pending = interviewers.filter(interviewer => interviewer.verified === 0).length;
                    const resumes = interviewers.filter(interviewer => interviewer.resume).length;

                    $('#totalInterviewers').text(total);
                    $('#verifiedInterviewers').text(verified);
                    $('#pendingInterviewers').text(pending);
                    $('#resumesUploaded').text(resumes);

                    // Load recent interviewers (last 5)
                    const recent = interviewers.slice(0, 5);
                    loadRecentInterviewers(recent);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    // Function to load recent interviewers
    function loadRecentInterviewers(interviewers) {
        const tbody = $('#interviewersTableBody');
        tbody.empty();

        if (interviewers.length === 0) {
            tbody.append(`
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No interviewers registered yet</p>
                        </td>
                    </tr>
                `);
            return;
        }

        interviewers.forEach(interviewer => {
            const statusBadge = interviewer.verified === 1 ?
                `<span class="status-badge status-verified">Verified</span>` :
                `<span class="status-badge status-pending">Pending</span>`;

            const resumeButton = interviewer.resume ? `
                    <a href="../api/admin.php?action=download-resume&resume=${interviewer.resume}" class="btn btn-info btn-sm" title="Download Resume">
                        <i class="fas fa-download"></i>
                    </a>
                ` : `<span class="text-muted">No resume</span>`;

            tbody.append(`
                    <tr>
                        <td>${interviewer.name}</td>
                        <td>${interviewer.email}</td>
                        <td>${interviewer.phone}</td>
                        <td>${interviewer.experience} years</td>
                        <td>${statusBadge}</td>
                        <td>${new Date(interviewer.created_at).toLocaleString()}</td>
                        <td>
                            <button class="btn btn-primary btn-sm view-btn" data-id="${interviewer.id}" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${resumeButton}
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${interviewer.id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
        });

        // Add event listeners to buttons
        $('.view-btn').click(function() {
            const id = $(this).data('id');
            viewInterviewer(id);
        });

        $('.delete-btn').click(function() {
            const id = $(this).data('id');
            deleteInterviewer(id);
        });
    }

    // Function to view interviewer details
    function viewInterviewer(id) {
        $.ajax({
            url: `../api/admin.php?action=interviewers&id=${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const interviewer = response.data;
                    const modalBody = $('#viewModalBody');

                    modalBody.html(`
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Personal Information</h5>
                                    <p><strong>Name:</strong> ${interviewer.name}</p>
                                    <p><strong>Email:</strong> ${interviewer.email}</p>
                                    <p><strong>Phone:</strong> ${interviewer.phone}</p>
                                    <p><strong>Address:</strong> ${interviewer.address}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">Professional Information</h5>
                                    <p><strong>Qualification:</strong> ${interviewer.qualification}</p>
                                    <p><strong>Experience:</strong> ${interviewer.experience} years</p>
                                    <p><strong>Created At:</strong> ${new Date(interviewer.created_at).toLocaleString()}</p>
                                    <p><strong>Status:</strong> ${interviewer.verified === 1 ? 
                                        '<span class="status-badge status-verified">Verified</span>' : 
                                        '<span class="status-badge status-pending">Pending</span>'}
                                    </p>
                                    ${interviewer.resume ? `
                                        <p><strong>Resume:</strong> 
                                            <a href="../api/admin.php?action=download-resume&resume=${interviewer.resume}" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </p>
                                    ` : '<p><strong>Resume:</strong> No resume uploaded</p>'}
                                </div>
                            </div>
                        `);

                    $('#viewModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                alert('Error loading interviewer details');
                console.error('Error:', error);
            }
        });
    }

    // Function to delete interviewer
    function deleteInterviewer(id) {
        if (confirm('Are you sure you want to delete this interviewer?')) {
            $.ajax({
                url: `../api/admin.php?action=delete-interviewer&id=${id}`,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Interviewer deleted successfully');
                        loadDashboardStats();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error deleting interviewer');
                    console.error('Error:', error);
                }
            });
        }
    }

    // Load stats on page load
    $(document).ready(function() {
        loadDashboardStats();
    });
    </script>
</body>

</html>