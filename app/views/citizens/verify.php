<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify Citizen ID · Barangay Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --font-family-sans-serif: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        body {
            font-family: var(--font-family-sans-serif);
            background-color: #f4f6f9;
            color: #495057;
        }

        .wrapper {
            display: flex;
            width: 100%;
        }

        #sidebar {
            width: 250px;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #23272b;
            text-align: center;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 15px 20px;
            font-size: 1.1em;
            display: block;
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: #007bff;
        }

        #sidebar ul li.active>a,
        a[aria-expanded="true"] {
            color: #fff;
            background: #007bff;
        }

        #content {
            width: calc(100% - 250px);
            padding: 40px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        #content.active {
            width: 100%;
        }

        .card {
            border: none;
            border-radius: .5rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, .05);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #dee2e6;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status-verified {
            background-color: #28a745;
            color: #fff;
        }

        .status-rejected {
            background-color: #dc3545;
            color: #fff;
        }

        .verification-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0.5rem;
            margin-bottom: 20px;
        }

        .verification-details h5 {
            color: #495057;
            margin-bottom: 15px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }

        .detail-label {
            font-weight: bold;
            width: 150px;
            flex-shrink: 0;
        }

        .detail-value {
            flex-grow: 1;
        }

        .btn-verify {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-verify:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-reject {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-reject:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Barangay Management</h3>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li>
                    <a href="<?= site_url('citizens') ?>"><i class="fas fa-users"></i> Citizens</a>
                </li>
                <li class="active">
                    <a href="<?= site_url('citizens/verifications') ?>"><i class="fas fa-id-card"></i> ID Verifications</a>
                </li>
                <li>
                    <a href="<?= site_url('documents') ?>"><i class="fas fa-file-alt"></i> Documents</a>
                </li>
                <li>
                    <a href="<?= site_url('permits') ?>"><i class="fas fa-certificate"></i> Permits</a>
                </li>
                <li>
                    <a href="<?= site_url('staff') ?>"><i class="fas fa-user-tie"></i> Staff</a>
                </li>
                <li>
                    <a href="<?= site_url('roles') ?>"><i class="fas fa-user-shield"></i> Roles</a>
                </li>
                <li>
                    <a href="<?= site_url('announcements') ?>"><i class="fas fa-bullhorn"></i> Announcements</a>
                </li>
                <li>
                    <a href="<?= site_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <i class="fas fa-align-left"></i>
                    <span>Toggle Sidebar</span>
                </button>
            </nav>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0"><i class="fas fa-id-card"></i> Verify Citizen ID</h4>
                            </div>
                            <div class="card-body">
                                <?php if (isset($citizen) && $citizen): ?>
                                    <div class="verification-details">
                                        <h5><i class="fas fa-user"></i> Citizen Information</h5>
                                        <div class="detail-row">
                                            <div class="detail-label">Full Name:</div>
                                            <div class="detail-value"><?= htmlspecialchars($citizen['first_name'] . ' ' . $citizen['middle_name'] . ' ' . $citizen['last_name']) ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Email:</div>
                                            <div class="detail-value"><?= htmlspecialchars($citizen['email']) ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Phone:</div>
                                            <div class="detail-value"><?= htmlspecialchars($citizen['phone'] ?? 'Not provided') ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Address:</div>
                                            <div class="detail-value"><?= htmlspecialchars($citizen['address'] ?? 'Not provided') ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Birth Date:</div>
                                            <div class="detail-value">
                                                <?php if (isset($citizen['birth_date'])): ?>
                                                    <?= date('F d, Y', strtotime($citizen['birth_date'])) ?>
                                                <?php else: ?>
                                                    Not provided
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Gender:</div>
                                            <div class="detail-value"><?= htmlspecialchars(ucfirst($citizen['gender'] ?? 'Not specified')) ?></div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">National ID:</div>
                                            <div class="detail-value">
                                                <strong style="font-size: 1.1em; color: #007bff;"><?= htmlspecialchars($citizen['national_id'] ?? 'Not provided') ?></strong>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Verification Status:</div>
                                            <div class="detail-value">
                                                <span class="status-badge status-<?= strtolower($citizen['verification_status'] ?? 'pending') ?>">
                                                    <?= ucfirst($citizen['verification_status'] ?? 'pending') ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Applied At:</div>
                                            <div class="detail-value">
                                                <?php if (isset($citizen['created_at'])): ?>
                                                    <?= date('F d, Y H:i', strtotime($citizen['created_at'])) ?>
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (($citizen['verification_status'] ?? 'pending') === 'pending' && !empty($citizen['national_id'])): ?>
                                        <div class="text-center">
                                            <p class="mb-4">Please review the citizen's information and National ID. Confirm if the provided details are valid and match the submitted National ID.</p>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="verification_action" value="verify">
                                                <button type="submit" class="btn btn-verify btn-lg mr-3">
                                                    <i class="fas fa-check"></i> Verify ID
                                                </button>
                                            </form>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="verification_action" value="reject">
                                                <button type="submit" class="btn btn-reject btn-lg" onclick="return confirm('Are you sure you want to reject this ID verification?')">
                                                    <i class="fas fa-times"></i> Reject Verification
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info text-center">
                                            <i class="fas fa-info-circle"></i> This citizen's ID verification has already been processed.
                                        </div>
                                    <?php endif; ?>

                                    <div class="text-center mt-4">
                                        <a href="<?= site_url('citizens/verifications') ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to Verifications
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-danger text-center">
                                        <i class="fas fa-exclamation-triangle"></i> Citizen not found or access denied.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
        });
    </script>
</body>

</html>
