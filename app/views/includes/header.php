<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= site_url() ?>">BMS</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('dashboard') ?>">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('citizens') ?>">Citizens</a>
            </li>
            <!-- Add other navigation links as needed -->
        </ul>
        <ul class="navbar-nav ml-auto">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="notifications-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="badge badge-danger" id="notifications-count"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifications-dropdown" id="notifications-list">
                        <!-- Notifications will be loaded here -->
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('auth/logout') ?>">Logout</a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('auth/login') ?>">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-4">

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    function fetchNotifications() {
        $.ajax({
            url: '<?= site_url('notifications/get_notifications') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let notifications = response.notifications;
                let count = notifications.length;
                $('#notifications-count').text(count > 0 ? count : '');
                let list = '';
                if (count > 0) {
                    for (let i = 0; i < notifications.length; i++) {
                        list += '<a class="dropdown-item" href="#">' + notifications[i].message + '</a>';
                    }
                } else {
                    list = '<a class="dropdown-item" href="#">No new notifications</a>';
                }
                $('#notifications-list').html(list);
            }
        });
    }

    fetchNotifications();
    setInterval(fetchNotifications, 5000); // Refresh every 5 seconds

    $('#notifications-dropdown').on('click', function() {
        $('#notifications-count').text('');
        $.ajax({
            url: '<?= site_url('notifications/mark_as_read') ?>',
            method: 'POST'
        });
    });
});
</script>
