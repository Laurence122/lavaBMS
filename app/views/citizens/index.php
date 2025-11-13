<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Citizens · Barangay Management System</title>
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

        #sidebar .sidebar-header {
            padding: 20px;
            background: #23272b;
            text-align: center;
        }

        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #47748b;
        }

        #sidebar ul p {
            color: #fff;
            padding: 10px;
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

        #content {
            width: calc(100% - 250px);
            padding: 40px;
            min-height: 100vh;
            transition: all 0.3s;
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

        .table {
            margin-bottom: 0;
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
                <h3>BMS Admin</h3>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="<?= site_url('dashboard/admin') ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('documents') ?>">
                        <i class="fas fa-file-alt"></i>
                        Documents
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('permits') ?>">
                        <i class="fas fa-certificate"></i>
                        Permits
                    </a>
                </li>
                <li class="active">
                    <a href="<?= site_url('users') ?>">
                        <i class="fas fa-users"></i>
                        Users
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Citizens</span>
                        <form action="<?= site_url('citizens') ?>" method="get" class="form-inline">
                            <input type="text" name="q" class="form-control mr-sm-2" placeholder="Search citizens..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                            <button type="submit" class="btn btn-outline-primary">Search</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Verification Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($citizens as $citizen) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($citizen['first_name'] . ' ' . $citizen['last_name']) ?></td>
                                            <td><?= htmlspecialchars($citizen['email']) ?></td>
                                            <td><?= htmlspecialchars($citizen['phone']) ?></td>
                                            <td><?= htmlspecialchars($citizen['address']) ?></td>
                                            <td>
                                                <?php if (isset($citizen['verification_status']) && $citizen['verification_status'] === 'verified'): ?>
                                                    <span class="badge badge-success">Verified</span>
                                                <?php elseif (isset($citizen['verification_status']) && $citizen['verification_status'] === 'rejected'): ?>
                                                    <span class="badge badge-danger">Rejected</span>
                                                <?php elseif (!empty($citizen['national_id'])): ?>
                                                    <span class="badge badge-warning">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">No ID</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('citizens/view/' . $citizen['id']) ?>" class="btn btn-primary btn-sm" title="View Profile">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="<?= site_url('citizens/update/' . $citizen['id']) ?>" class="btn btn-info btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="<?= site_url('citizens/delete/' . $citizen['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')" title="Delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
        });
    </script>
</body>

</html>
