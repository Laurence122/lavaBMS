<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Documents · Barangay Management System</title>
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

        .badge {
            font-size: .8rem;
            padding: .4em .6em;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .countdown-timer {
            font-weight: bold;
            color: #007bff;
        }

        .countdown-expired {
            color: #dc3545;
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
                <li class="active">
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
                <li>
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
                    <div class="card-header">
                        <span>All Document Requests</span>
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
                                        <th>Requested By</th>
                                        <th>Requested On</th>
                                        <th>Pickup Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($documents as $doc) : ?>
                                        <tr>
                                            <td><?= ucfirst(str_replace('_', ' ', $doc['document_type'])) ?></td>
                                            <td><?= htmlspecialchars($doc['purpose']) ?></td>
                                            <td><span class="badge status-<?= $doc['status'] ?>"><?= ucfirst($doc['status']) ?></span></td>
                                            <td>₱<?= isset($doc['fee']) ? number_format($doc['fee'], 2) : '0.00' ?></td>
                                            <td><?= htmlspecialchars($doc['username']) ?></td>
                                            <td><?= date('M d, Y', strtotime($doc['requested_at'])) ?></td>
                                            <td>
                                                <?php if (isset($doc['pickup_time']) && !empty($doc['pickup_time'])): ?>
                                                    <div id="countdown-<?= $doc['id'] ?>" class="countdown-timer" data-pickup-time="<?= $doc['pickup_time'] ?>">
                                                        <?= date('M d, Y H:i', strtotime($doc['pickup_time'])) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <button class="btn btn-info btn-sm" onclick="setPickupTime(<?= $doc['id'] ?>, '<?= htmlspecialchars($doc['username']) ?>')">Set Time</button>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($doc['status'] === 'pending') : ?>
                                                    <a href="<?= site_url('documents/update_status/' . $doc['id'] . '?status=approved') ?>" class="btn btn-success btn-sm">Approve</a>
                                                <?php elseif ($doc['status'] === 'approved_pending_payment') : ?>
                                                    <a href="<?= site_url('documents/update_status/' . $doc['id'] . '?status=approved') ?>" class="btn btn-success btn-sm">Approve</a>
                                                <?php endif; ?>
                                                <a href="<?= site_url('documents/update_status/' . $doc['id'] . '?status=rejected') ?>" class="btn btn-danger btn-sm">Reject</a>
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

    <!-- Set Fee Modal -->
    <div class="modal fade" id="setFeeModal" tabindex="-1" role="dialog" aria-labelledby="setFeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setFeeModalLabel">Set Document Fee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Document Type:</strong> <span id="modalDocumentType"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Purpose:</strong> <span id="modalPurpose"></span>
                    </div>
                    <form id="setFeeForm">
                        <div class="form-group">
                            <label for="feeAmount">Fee Amount (₱)</label>
                            <input type="number" class="form-control" id="feeAmount" name="fee" step="0.01" min="0" required>
                        </div>
                        <input type="hidden" id="documentId" name="document_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitFee()">Set Fee & Approve</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Set Pickup Time Modal -->
    <div class="modal fade" id="setPickupTimeModal" tabindex="-1" role="dialog" aria-labelledby="setPickupTimeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setPickupTimeModalLabel">Set Pickup Time</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Requested By:</strong> <span id="modalUsername"></span>
                    </div>
                    <form id="setPickupTimeForm">
                        <div class="form-group">
                            <label for="pickupTime">Pickup Date & Time</label>
                            <input type="datetime-local" class="form-control" id="pickupTime" name="pickup_time" required>
                        </div>
                        <input type="hidden" id="pickupDocumentId" name="document_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitPickupTime()">Set Pickup Time</button>
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

        function setFee(documentId, documentType, purpose, currentFee) {
            $('#documentId').val(documentId);
            $('#modalDocumentType').text(documentType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()));
            $('#modalPurpose').text(purpose);
            $('#feeAmount').val(currentFee > 0 ? currentFee : '');
            $('#setFeeModal').modal('show');
        }

        function submitFee() {
            const documentId = $('#documentId').val();
            const feeAmount = $('#feeAmount').val();

            if (!feeAmount || feeAmount < 0) {
                alert('Please enter a valid fee amount.');
                return;
            }

            $.ajax({
                url: '<?= site_url('documents/set_fee') ?>',
                type: 'POST',
                data: {
                    document_id: documentId,
                    fee: feeAmount
                },
                success: function(response) {
                    $('#setFeeModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    alert('Error setting fee. Please try again.');
                }
            });
        }

        function setPickupTime(documentId, username) {
            $('#pickupDocumentId').val(documentId);
            $('#modalUsername').text(username);
            $('#setPickupTimeModal').modal('show');
        }

        function submitPickupTime() {
            const documentId = $('#pickupDocumentId').val();
            const pickupTime = $('#pickupTime').val();

            if (!pickupTime) {
                alert('Please select a pickup date and time.');
                return;
            }

            $.ajax({
                url: '<?= site_url('documents/set_pickup_time') ?>',
                type: 'POST',
                data: {
                    document_id: documentId,
                    pickup_time: pickupTime
                },
                success: function(response) {
                    $('#setPickupTimeModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    alert('Error setting pickup time. Please try again.');
                }
            });
        }

        function updateCountdowns() {
            $('.countdown-timer').each(function() {
                const element = $(this);
                const pickupTime = new Date(element.data('pickup-time')).getTime();
                const now = new Date().getTime();
                const distance = pickupTime - now;

                if (distance > 0) {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    let countdownText = '';
                    if (days > 0) {
                        countdownText = days + "d " + hours + "h " + minutes + "m " + seconds + "s";
                    } else if (hours > 0) {
                        countdownText = hours + "h " + minutes + "m " + seconds + "s";
                    } else if (minutes > 0) {
                        countdownText = minutes + "m " + seconds + "s";
                    } else {
                        countdownText = seconds + "s";
                    }

                    element.html(countdownText + '<br><small>' + element.data('pickup-time').replace('T', ' ').substring(0, 16) + '</small>');
                    element.removeClass('countdown-expired');
                } else {
                    element.html('EXPIRED<br><small>' + element.data('pickup-time').replace('T', ' ').substring(0, 16) + '</small>');
                    element.addClass('countdown-expired');
                }
            });
        }

        // Update countdowns every second
        setInterval(updateCountdowns, 1000);

        // Initial update
        updateCountdowns();
    </script>
</body>

</html>
