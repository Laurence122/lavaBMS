<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard · Barangay Management System</title>
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
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>BMS Admin</h3>
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
                    <a href="<?= site_url('users') ?>"><i class="fas fa-users mr-3"></i>Users</a>
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
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card" style="background: linear-gradient(to right, #ff9966, #ff5e62); color: #fff;">
                            <div class="card-body">
                                <div>
                                    <h3><?= count($all_documents) ?></h3>
                                    <p>Total Documents</p>
                                </div>
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card" style="background: linear-gradient(to right, #64b5f6, #2196f3); color: #fff;">
                            <div class="card-body">
                                <div>
                                    <h3><?= count($all_permits) ?></h3>
                                    <p>Total Permits</p>
                                </div>
                                <i class="fas fa-certificate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card stat-card" style="background: linear-gradient(to right, #ffc107, #ff8f00); color: #fff;">
                            <div class="card-body">
                                <div>
                                    <h3><?= count($staff_tasks) ?></h3>
                                    <p>Staff Tasks</p>
                                </div>
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                Document Type Distribution
                            </div>
                            <div class="card-body">
                                <canvas id="documentTypeChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                Staff Accomplishments
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Staff Name</th>
                                                <th>Task</th>
                                                <th>Status</th>
                                                <th>Date Assigned</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($staff_tasks as $task) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($task['staff_name']) ?></td>
                                                    <td><?= htmlspecialchars($task['task_description']) ?></td>
                                                    <td><?= htmlspecialchars($task['status']) ?></td>
                                                    <td><?= date('M d, Y', strtotime($task['created_at'])) ?></td>
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
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            // Chart initialization with enhanced styling
            var ctx = document.getElementById("documentTypeChart");
            if (ctx) {
                ctx = ctx.getContext('2d');
                var documentTypeData = JSON.parse('<?php echo $document_type_distribution; ?>');

                var labels = documentTypeData.map(function(item) {
                    return item.document_type;
                });

                var counts = documentTypeData.map(function(item) {
                    return item.count;
                });

                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: counts,
                            backgroundColor: [
                                '#ff1493',
                                '#8a2be2',
                                '#ff69b4',
                                '#4b0082',
                                '#ffb6c1',
                                '#dda0dd'
                            ],
                            borderWidth: 3,
                            borderColor: 'rgba(255, 255, 255, 0.8)',
                            hoverBorderWidth: 5,
                            hoverBorderColor: 'rgba(255, 20, 147, 0.8)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'right',
                            labels: {
                                fontColor: '#1a1a1a',
                                fontSize: 14,
                                fontFamily: 'Poppins',
                                padding: 20,
                            }
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true,
                            duration: 2000,
                            easing: 'easeInOutQuart'
                        },
                        tooltips: {
                            backgroundColor: 'rgba(255, 20, 147, 0.9)',
                            titleFontColor: '#ffffff',
                            bodyFontColor: '#ffffff',
                            cornerRadius: 10,
                            displayColors: false,
                        }
                    }
                });
            }

            // Auto-refresh dashboard data every 60 seconds
            setInterval(function() {
                // You can add AJAX calls here to refresh dashboard data
                console.log('Admin dashboard refresh check...');
            }, 60000);

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

            // Add loading states for actions
            $('.btn').on('click', function() {
                if ($(this).hasClass('btn-primary') || $(this).hasClass('btn-success')) {
                    $(this).addClass('loading');
                }
            });
        });
    </script>
</body>

</html>
