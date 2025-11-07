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
        :root {
            --primary-pink: #ff1493;
            --secondary-purple: #8a2be2;
            --accent-purple: #4b0082;
            --dark-black: #1a1a1a;
            --light-white: #ffffff;
            --soft-pink: #ffe6f2;
            --gradient-pink: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            --gradient-purple: linear-gradient(135deg, #8a2be2 0%, #4b0082 100%);
            --shadow: 0 10px 30px rgba(255, 20, 147, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--soft-pink);
            color: var(--dark-black);
            overflow-x: hidden;
        }

        .wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        #sidebar {
            width: 280px;
            background: var(--gradient-purple);
            color: var(--light-white);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow);
            position: relative;
        }

        #sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        #sidebar.active {
            margin-left: -280px;
        }

        #sidebar .sidebar-header {
            padding: 30px 25px;
            background: var(--gradient-pink);
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            position: relative;
            z-index: 2;
            border-radius: 0 0 20px 20px;
        }

        #sidebar ul.components {
            padding: 30px 0;
            position: relative;
            z-index: 2;
        }

        #sidebar ul li a {
            padding: 18px 30px;
            font-size: 1.1em;
            display: block;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            border-left: 4px solid transparent;
        }

        #sidebar ul li a:hover {
            color: var(--light-white);
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--primary-pink);
            padding-left: 35px;
            transform: translateX(5px);
        }

        #sidebar ul li.active>a {
            color: var(--light-white);
            background: rgba(255, 20, 147, 0.2);
            border-left: 4px solid var(--primary-pink);
        }

        #content {
            width: calc(100% - 280px);
            padding: 40px;
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--soft-pink);
        }

        #content.active {
            width: 100%;
        }

        .navbar {
            background: var(--light-white);
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            border: none;
            backdrop-filter: blur(10px);
        }

        .navbar .btn-info {
            background: var(--gradient-pink);
            border: none;
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .navbar .btn-info:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 20, 147, 0.3);
        }

        .welcome-text {
            font-weight: 600;
            color: var(--dark-black);
            font-size: 1.1rem;
        }

        .card {
            background: var(--light-white);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-pink);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(255, 20, 147, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 230, 242, 0.9) 100%);
            border-bottom: 1px solid rgba(255, 20, 147, 0.1);
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-black);
            padding: 25px 30px;
            backdrop-filter: blur(10px);
        }

        .stat-card {
            background: var(--light-white);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 20, 147, 0.1), transparent);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stat-card .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 30px;
            position: relative;
            z-index: 2;
        }

        .stat-card h3 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 5px;
            background: var(--gradient-pink);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card p {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--secondary-purple);
            margin: 0;
        }

        .stat-card i {
            font-size: 4rem;
            opacity: 0.3;
            color: var(--primary-pink);
        }

        .table {
            background: var(--light-white);
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background: var(--gradient-purple);
            color: var(--light-white);
            font-weight: 600;
            border: none;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(255, 20, 147, 0.1);
        }

        .table tbody tr:hover {
            background: rgba(255, 20, 147, 0.05);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: var(--dark-black);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 2px solid;
        }

        .status-approved {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: var(--light-white);
            border-color: #28a745;
        }

        .status-paid {
            background: var(--gradient-pink);
            color: var(--light-white);
            border-color: var(--primary-pink);
        }

        .status-pending {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: var(--dark-black);
            border-color: #ffc107;
        }

        .status-rejected {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
            color: var(--light-white);
            border-color: #dc3545;
        }

        .btn-primary {
            background: var(--gradient-pink);
            border: none;
            border-radius: 25px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(255, 20, 147, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 20, 147, 0.4);
            background: var(--gradient-pink);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .alert {
            border-radius: 15px;
            border: none;
            box-shadow: var(--shadow);
            background: var(--light-white);
            color: var(--dark-black);
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
            border-left: 4px solid #ffc107;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .modal-header {
            background: var(--gradient-pink);
            color: var(--light-white);
            border: none;
            padding: 25px 30px;
        }

        .modal-body {
            padding: 30px;
            background: var(--soft-pink);
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid rgba(255, 20, 147, 0.2);
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(255, 20, 147, 0.25);
        }

        .form-group label {
            font-weight: 600;
            color: var(--secondary-purple);
            margin-bottom: 8px;
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        .slide-up {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Loading animation */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            #sidebar {
                width: 100%;
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 1000;
            }

            #sidebar.active {
                margin-left: -100%;
            }

            #content {
                width: 100%;
                padding: 20px;
            }

            .stat-card .card-body {
                flex-direction: column;
                text-align: center;
            }

            .stat-card h3 {
                font-size: 2.5rem;
            }

            .stat-card i {
                font-size: 3rem;
                margin-top: 10px;
            }
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
            // Sidebar toggle with smooth animation
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });

            // Add fade-in animation to cards
            $('.card').each(function(index) {
                $(this).css('opacity', '0').delay(index * 200).animate({opacity: 1}, 600);
            });

            // Enhanced table row hover effects
            $('.table tbody tr').hover(
                function() {
                    $(this).addClass('slide-up');
                },
                function() {
                    $(this).removeClass('slide-up');
                }
            );

            // Button click animations
            $('.btn').on('click', function() {
                $(this).addClass('loading');
                setTimeout(() => {
                    $(this).removeClass('loading');
                }, 1500);
            });

            // Modal enhancements
            $('.modal').on('show.bs.modal', function() {
                $(this).find('.modal-content').addClass('fade-in');
            });

            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                }
            });

            // Status badge animations
            $('.status-badge').each(function() {
                $(this).hover(
                    function() {
                        $(this).css('transform', 'scale(1.05)');
                    },
                    function() {
                        $(this).css('transform', 'scale(1)');
                    }
                );
            });

            // Welcome message animation
            $('.welcome-text').hide().fadeIn(1000);

            // Card hover effects with jQuery
            $('.card').hover(
                function() {
                    $(this).find('.card-body').css('transform', 'translateY(-5px)');
                },
                function() {
                    $(this).find('.card-body').css('transform', 'translateY(0)');
                }
            );

            // Auto-refresh stats every 30 seconds
            setInterval(function() {
                // You can add AJAX calls here to refresh dashboard data
                console.log('Dashboard refresh check...');
            }, 30000);
        });
    </script>
</body>
</html>
