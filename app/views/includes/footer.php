</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php if ($logged_in_user['role'] === 'staff' || $logged_in_user['role'] === 'admin'): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function fetchNotifications() {
            fetch('<?= site_url('notifications/get_notifications') ?>')
                .then(response => response.text())
                .then(data => {
                    if (data.trim().length > 0) {
                        const notificationContainer = document.createElement('div');
                        notificationContainer.innerHTML = data;
                        const firstNotification = notificationContainer.querySelector('.alert');
                        if (firstNotification) {
                            const notificationPopup = document.getElementById('notification-popup');
                            notificationPopup.innerHTML = firstNotification.innerHTML;
                            notificationPopup.style.display = 'block';
                            setTimeout(() => {
                                notificationPopup.style.display = 'none';
                                fetch('<?= site_url('notifications/mark_as_read') ?>');
                            }, 5000);
                        }
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        setInterval(fetchNotifications, 10000); // Check for new notifications every 10 seconds
    });
</script>
<?php endif; ?>

</body>
</html>
