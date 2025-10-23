<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Staff Dashboard · Barangay Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #495057;
        }

        .wrapper {
            display: flex;
            width: 100%;
        }

        #sidebar {
            width: 260px;
            background: linear-gradient(to bottom, #232526, #414345);
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar.active {
            margin-left: -260px;
        }

        #sidebar .sidebar-header {
            padding: 25px;
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            text-align: center;
            font-size: 1.6rem;
            font-weight: 600;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 15px 25px;
            font-size: 1.1em;
            display: block;
            color: #d0d0d0;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #8e2de2;
            padding-left: 21px;
        }

        #sidebar ul li.active>a,
        a[aria-expanded="true"] {
            color: #fff;
            background: rgba(142, 45, 226, 0.3);
        }

        #content {
            width: calc(100% - 260px);
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        #content.active {
            width: 100%;
        }

        .navbar {
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
            transition: all 0.3s ease;
        }
        .card:hover{
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,.1);
        }

        .card-header {
            background-color: #fff;
            border-bottom: none;
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            padding: 20px;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-success {
            border-radius: 20px;
            padding: 5px 15px;
        }
        .btn-danger {
            border-radius: 20px;
            padding: 5px 15px;
        }

    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>BMS Staff</h3>
            </div>
            <ul class="list-unstyled components">
                <li class="active">
                    <a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-3"></i>Dashboard</a>
                </li>
                <li>
                    <a href="<?= site_url('documents') ?>"><i class="fas fa-file-alt mr-3"></i>Documents</a>
                </li>
                <li>
                    <a href="<?= site_url('permits') ?>"><i class="fas fa-certificate mr-3"></i>Permits</a>
                </li>
                <li>
                    <a href="<?= site_url('auth/logout') ?>"><i class="fas fa-sign-out-alt mr-3"></i>Logout</a>
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
                <div id="notification-popup" class="alert alert-info" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 1050; width: 300px;">
                    A new document has been requested.
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Pending Document Requests</h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($pending_documents)) : ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Document Type</th>
                                                    <th>Purpose</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pending_documents as $doc) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($doc['document_type']) ?></td>
                                                        <td><?= htmlspecialchars($doc['purpose']) ?></td>
                                                        <td>
                                                            <a href="<?= site_url('documents/update_status/' . $doc['id'] . '?status=approved') ?>" class="btn btn-success btn-sm">Approve</a>
                                                            <a href="<?= site_url('documents/update_status/' . $doc['id'] . '?status=rejected') ?>" class="btn btn-danger btn-sm">Reject</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <p>No pending document requests.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Pending Permit Applications</h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($pending_permits)) : ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Business Name</th>
                                                    <th>Permit Type</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pending_permits as $permit) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($permit['business_name']) ?></td>
                                                        <td><?= htmlspecialchars($permit['permit_type']) ?></td>
                                                        <td>
                                                            <a href="<?= site_url('permits/update_status/' . $permit['id'] . '?status=approved') ?>" class="btn btn-success btn-sm">Approve</a>
                                                            <a href="<?= site_url('permits/update_status/' . $permit['id'] . '?status=rejected') ?>" class="btn btn-danger btn-sm">Reject</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <p>No pending permit applications.</p>
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
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
        });
    </script>
</body>

</html>
