<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ID Verifications · Barangay Management System</title>
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
                                <h4 class="card-title mb-0"><i class="fas fa-id-card"></i> ID Verifications</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>National ID</th>
                                                <th>Verification Status</th>
                                                <th>Applied At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($citizens)): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No citizens requiring verification found.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($citizens as $citizen): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($citizen['first_name'] . ' ' . $citizen['last_name']) ?></td>
                                                        <td><?= htmlspecialchars($citizen['email']) ?></td>
                                                        <td><?= htmlspecialchars($citizen['national_id'] ?? 'Not provided') ?></td>
                                                        <td>
                                                            <span class="status-badge status-<?= strtolower($citizen['verification_status'] ?? 'pending') ?>">
                                                                <?= ucfirst($citizen['verification_status'] ?? 'pending') ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($citizen['created_at'])): ?>
                                                                <?= date('M d, Y H:i', strtotime($citizen['created_at'])) ?>
                                                            <?php else: ?>
                                                                N/A
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (($citizen['verification_status'] ?? 'pending') === 'pending' && !empty($citizen['national_id'])): ?>
                                                                <a href="<?= site_url('citizens/verify/' . $citizen['id']) ?>" class="btn btn-verify btn-sm">
                                                                    <i class="fas fa-check"></i> Verify
                                                                </a>
                                                                <button type="button" class="btn btn-reject btn-sm" onclick="rejectVerification(<?= $citizen['id'] ?>)">
                                                                    <i class="fas fa-times"></i> Reject
                                                                </button>
                                                            <?php else: ?>
                                                                <span class="text-muted">No action needed</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
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

        function rejectVerification(citizenId) {
            if (confirm('Are you sure you want to reject this ID verification?')) {
                // Create a form to submit the rejection
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= site_url('citizens/verify/' . $citizen['id'] ?? '') ?>'.replace('<?= $citizen['id'] ?? '' ?>', citizenId);

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'verification_action';
                input.value = 'reject';

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>

</html>
