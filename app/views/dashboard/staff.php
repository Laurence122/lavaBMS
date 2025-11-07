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

        .badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 2px solid;
        }

        .badge-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: var(--dark-black);
            border-color: #ffc107;
        }

        .badge-info {
            background: var(--gradient-pink);
            color: var(--light-white);
            border-color: var(--primary-pink);
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

        .alert-info {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(23, 162, 184, 0.05));
            border-left: 4px solid #17a2b8;
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
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pending_documents as $doc) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($doc['document_type']) ?></td>
                                                        <td><?= htmlspecialchars($doc['purpose']) ?></td>
                                                        <td><span class="badge badge-warning">Pending</span></td>
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
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pending_permits as $permit) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($permit['business_name']) ?></td>
                                                        <td><?= htmlspecialchars($permit['permit_type']) ?></td>
                                                        <td><span class="badge badge-warning">Pending</span></td>
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

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-success">Paid Documents (Ready for Final Approval)</h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($paid_documents)) : ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Document Type</th>
                                                    <th>Purpose</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($paid_documents as $doc) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($doc['document_type']) ?></td>
                                                        <td><?= htmlspecialchars($doc['purpose']) ?></td>
                                                        <td><span class="badge badge-info">Paid</span></td>
                                                        <td>
                                                            <a href="<?= site_url('documents/update_status/' . $doc['id'] . '?status=paid') ?>" class="btn btn-success btn-sm">Approve</a>
                                                            <a href="<?= site_url('documents/update_status/' . $doc['id'] . '?status=rejected') ?>" class="btn btn-danger btn-sm">Reject</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <p>No paid documents waiting for approval.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-success">Paid Permits (Ready for Final Approval)</h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($paid_permits)) : ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Business Name</th>
                                                    <th>Permit Type</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($paid_permits as $permit) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($permit['business_name']) ?></td>
                                                        <td><?= htmlspecialchars($permit['permit_type']) ?></td>
                                                        <td><span class="badge badge-info">Paid</span></td>
                                                        <td>
                                                            <a href="<?= site_url('permits/update_status/' . $permit['id'] . '?status=paid') ?>" class="btn btn-success btn-sm">Approve</a>
                                                            <a href="<?= site_url('permits/update_status/' . $permit['id'] . '?status=rejected') ?>" class="btn btn-danger btn-sm">Reject</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <p>No paid permits waiting for approval.</p>
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

            // Badge animations
            $('.badge').each(function() {
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

            // Notification popup auto-hide
            setTimeout(function() {
                $('#notification-popup').fadeOut(500);
            }, 5000);

            // Auto-refresh pending counts every 30 seconds
            setInterval(function() {
                // You can add AJAX calls here to refresh pending counts
                console.log('Staff dashboard refresh check...');
            }, 30000);

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

            // Enhanced modal animations
            $('.modal').on('show.bs.modal', function() {
                $(this).find('.modal-content').addClass('fade-in');
            });

            // Table sorting functionality (basic)
            $('.table thead th').on('click', function() {
                var table = $(this).parents('table').eq(0);
                var rows = table.find('tr:gt(0)').toArray().sort(function(a, b) {
                    var aVal = $(a).children('td').eq($(this).index()).text();
                    var bVal = $(b).children('td').eq($(this).index()).text();
                    return aVal.localeCompare(bVal);
                });
                this.asc = !this.asc;
                if (!this.asc) {
                    rows = rows.reverse();
                }
                for (var i = 0; i < rows.length; i++) {
                    table.append(rows[i]);
                }
            });
        });
    </script>
</body>

</html>
