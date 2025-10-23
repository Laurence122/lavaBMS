<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile · Barangay Management System</title>
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
            font-size: 1.2rem;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>BMS</h3>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="<?= site_url('dashboard') ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('documents/my_documents') ?>">
                        <i class="fas fa-file-alt"></i>
                        My Documents
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('permits/my_permits') ?>">
                        <i class="fas fa-certificate"></i>
                        My Permits
                    </a>
                </li>
                <li class="active">
                    <a href="<?= site_url('citizens/profile') ?>">
                        <i class="fas fa-user"></i>
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('auth/logout') ?>">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                    </button>
                    <div class="ml-auto">
                        Welcome, <strong><?= htmlspecialchars($logged_in_user['username']); ?></strong>
                    </div>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        My Profile
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?= site_url('citizens/profile') ?>">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($citizen['first_name'] ?? '') ?>" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= htmlspecialchars($citizen['middle_name'] ?? '') ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($citizen['last_name'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($citizen['email'] ?? '') ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($citizen['phone'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($citizen['address'] ?? '') ?></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="birth_date">Birth Date</label>
                                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?= htmlspecialchars($citizen['birth_date'] ?? '') ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="Male" <?= (isset($citizen['gender']) && $citizen['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= (isset($citizen['gender']) && $citizen['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="national_id">National ID</label>
                                <input type="text" class="form-control" id="national_id" name="national_id" value="<?= htmlspecialchars($citizen['national_id'] ?? '') ?>" placeholder="Enter your National ID for verification">
                                <small class="form-text text-muted">Required for document requests and permit applications.</small>
                                <?php if (isset($citizen['verification_status'])): ?>
                                    <small class="form-text text-info">Verification Status: <strong><?= ucfirst($citizen['verification_status']) ?></strong></small>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="verification_action" id="verification_action" value="">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <?php if (isset($citizen['national_id']) && !empty($citizen['national_id'])): ?>
                                <?php if (($citizen['verification_status'] ?? 'pending') !== 'verified'): ?>
                                    <button type="button" class="btn btn-success ml-2" onclick="setAction('verify')">Verify ID</button>
                                <?php endif; ?>
                                <?php if (($citizen['verification_status'] ?? 'pending') !== 'rejected'): ?>
                                    <button type="button" class="btn btn-danger ml-2" onclick="setAction('reject')">Reject Verification</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </form>
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

        function setAction(action) {
            document.getElementById('verification_action').value = action;
            document.querySelector('form').submit();
        }
    </script>
</body>

</html>
