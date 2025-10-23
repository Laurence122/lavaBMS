<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Citizen Dashboard · Barangay Management System</title>
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

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, .1);
        }

        .card-header {
            background-color: #fff;
            border-bottom: none;
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            padding: 20px;
        }

        .stat-card .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 25px;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .stat-card i {
            font-size: 3.5rem;
            opacity: 0.2;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .status-approved {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-paid {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-rejected {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .btn-primary {
            background-color: #8e2de2;
            border-color: #8e2de2;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4a00e0;
            border-color: #4a00e0;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>BMS</h3>
            </div>
            <ul class="list-unstyled components">
                <li class="active">
                    <a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-3"></i>Dashboard</a>
                </li>
                <li>
                    <a href="#requests-sub" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-file-alt mr-3"></i>My Requests</a>
                    <ul class="collapse list-unstyled" id="requests-sub">
                        <li><a href="#my-documents">Document Requests</a></li>
                        <li><a href="#my-permits">Business Permits</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?= site_url('citizens/profile') ?>"><i class="fas fa-user mr-3"></i>Profile</a>
                </li>
                <li>
                    <a href="<?= site_url('auth/logout') ?>"><i class="fas fa-sign-out-alt mr-3"></i>Logout</a>
                </li>
            </ul>
        </nav>

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
                <?php if (empty($citizen_profile)) : ?>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        Please complete your citizen profile to access all services. <a href="<?= site_url('citizens/profile') ?>" class="alert-link">Complete Profile</a>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card" style="background: linear-gradient(to right, #ff9966, #ff5e62); color: #fff;">
                            <div class="card-body">
                                <div>
                                    <h3><?= count($my_documents) ?></h3>
                                    <p>Document Requests</p>
                                </div>
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card" style="background: linear-gradient(to right, #64b5f6, #2196f3); color: #fff;">
                            <div class="card-body">
                                <div>
                                    <h3><?= count($my_permits) ?></h3>
                                    <p>Business Permits</p>
                                </div>
                                <i class="fas fa-certificate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card" style="background: linear-gradient(to right, #4ecdc4, #44a08d); color: #fff;">
                            <div class="card-body">
                                <div>
                                    <?php
                                    $pending_payments = 0;
                                    foreach ($my_documents as $doc) {
                                        if ($doc['status'] === 'approved_pending_payment') $pending_payments++;
                                    }
                                    foreach ($my_permits as $permit) {
                                        if ($permit['status'] === 'approved_pending_payment') $pending_payments++;
                                    }
                                    ?>
                                    <h3><?= $pending_payments ?></h3>
                                    <p>Pending Payments</p>
                                </div>
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4" id="my-documents">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>My Document Requests</span>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#requestDocumentModal"><i class="fas fa-plus"></i> Request Document</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>Fee</th>
                                        <th>Requested On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($my_documents as $doc) : ?>
                                        <tr>
                                            <td><?= ucfirst(str_replace('_', ' ', $doc['document_type'])) ?></td>
                                            <td><?= htmlspecialchars($doc['purpose']) ?></td>
                                            <td><span class="status-badge status-<?= $doc['status'] ?>"><?= ucfirst(str_replace('_', ' ', $doc['status'])) ?></span></td>
                                            <td>₱<?= isset($doc['fee']) ? number_format($doc['fee'], 2) : '50.00' ?></td>
                                            <td><?= date('M d, Y', strtotime($doc['requested_at'])) ?></td>
                                            <td>
                                                <?php if ($doc['status'] === 'approved_pending_payment') : ?>
                                                    <a href="<?= site_url('documents/payment/' . $doc['id']) ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-credit-card mr-1"></i>Pay ₱<?= isset($doc['fee']) ? number_format($doc['fee'], 2) : '50.00' ?>
                                                    </a>
                                                <?php elseif ($doc['status'] === 'pending') : ?>
                                                    <a href="<?= site_url('documents/payment/' . $doc['id']) ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-credit-card mr-1"></i>Pay
                                                    </a>
                                                    <a href="<?= site_url('documents/cancel/' . $doc['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this request?');">
                                                        <i class="fas fa-times mr-1"></i>Cancel
                                                    </a>
                                                <?php elseif ($doc['status'] === 'paid') : ?>
                                                    <span class="text-muted">Waiting for Approval</span>
                                                <?php elseif ($doc['status'] === 'approved') : ?>
                                                    <a href="<?= site_url('documents/download/' . $doc['id']) ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-download mr-1"></i>Download
                                                    </a>
                                                <?php else : ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4" id="my-permits">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>My Business Permits</span>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#applyPermitModal"><i class="fas fa-plus"></i> Apply for Permit</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Business Name</th>
                                        <th>Business Type</th>
                                        <th>Status</th>
                                        <th>Fee</th>
                                        <th>Applied On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($my_permits as $permit) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($permit['business_name']) ?></td>
                                            <td><?= htmlspecialchars($permit['permit_type'] ?? 'N/A') ?></td>
                                            <td><span class="status-badge status-<?= $permit['status'] ?>"><?= ucfirst(str_replace('_', ' ', $permit['status'])) ?></span></td>
                                            <td>₱500.00</td>
                                            <td><?= date('M d, Y', strtotime($permit['applied_at'])) ?></td>
                                            <td>
                                                <?php if ($permit['status'] === 'approved_pending_payment') : ?>
                                                    <a href="<?= site_url('permits/payment/' . $permit['id']) ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-credit-card mr-1"></i>Pay ₱500.00
                                                    </a>
                                                <?php elseif ($permit['status'] === 'pending') : ?>
                                                    <a href="<?= site_url('permits/payment/' . $permit['id']) ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-credit-card mr-1"></i>Pay
                                                    </a>
                                                    <a href="<?= site_url('permits/cancel/' . $permit['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this permit application?');">
                                                        <i class="fas fa-times mr-1"></i>Cancel
                                                    </a>
                                                <?php elseif ($permit['status'] === 'paid') : ?>
                                                    <span class="text-muted">Processing</span>
                                                <?php elseif ($permit['status'] === 'approved') : ?>
                                                    <span class="text-muted">Ready</span>
                                                <?php else : ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
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

    <div class="modal fade" id="requestDocumentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request a Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= site_url('documents/request') ?>" method="post">
                        <div class="form-group">
                            <label for="nationalId">National ID (for verification)</label>
                            <input type="text" class="form-control" id="nationalId" name="national_id" placeholder="Enter your National ID" required>
                        </div>
                        <div class="form-group">
                            <label for="documentType">Document Type</label>
                            <select class="form-control" id="documentType" name="document_type">
                                <option value="certificate_of_indigency">Barangay Certificate of Indigency</option>
                                <option value="clearance">Barangay Clearance</option>
                                <option value="residency_certificate">Barangay Residency Certificate</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="purpose">Purpose</label>
                            <textarea class="form-control" id="purpose" name="purpose" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="applyPermitModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Apply for Business Permit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= site_url('permits/apply') ?>" method="post">
                        <div class="form-group">
                            <label for="businessName">Business Name</label>
                            <input type="text" class="form-control" id="businessName" name="business_name" placeholder="Enter business name" required>
                        </div>
                        <div class="form-group">
                            <label for="businessAddress">Business Address</label>
                            <input type="text" class="form-control" id="businessAddress" name="business_address" placeholder="Enter business address" required>
                        </div>
                        <div class="form-group">
                            <label for="ownerName">Owner Name</label>
                            <input type="text" class="form-control" id="ownerName" name="owner_name" placeholder="Enter owner name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </form>
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
