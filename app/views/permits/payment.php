<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include_once(APP_DIR . 'views/includes/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Payment for Business Permit</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Permit Details</h5>
                            <p><strong>Business Name:</strong> <?= htmlspecialchars($permit['business_name']) ?></p>
                            <p><strong>Business Address:</strong> <?= htmlspecialchars($permit['business_address']) ?></p>
                            <p><strong>Owner:</strong> <?= htmlspecialchars($permit['owner_name']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Details</h5>
                            <p><strong>Amount:</strong> $<?= number_format($amount, 2) ?></p>
                            <p><strong>Permit Fee:</strong> Business Permit Application</p>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <h5>Choose Payment Method</h5>
        <form id="payment-form" action="<?php echo BASE_URL; ?>/payment/create" method="post">
                            <input type="hidden" name="permit_id" value="<?= $permit['id'] ?>">
                            <input type="hidden" name="amount" value="<?= $amount ?>">
                            <input type="hidden" id="payment_type" name="payment_type" value="online">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" checked>
                                <label class="form-check-label" for="paypal">
                                    PayPal
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                                <label class="form-check-label" for="cash">
                                    Cash on Pickup
                                </label>
                            </div>

                            <div class="mt-4" id="paypal-section">
                                <div id="paypal-button-container"></div>
                            </div>

                            <div class="mt-4" id="cash-section" style="display: none;">
                                <p>You have selected Cash on Pickup. You will pay the fee when you pick up your permit after approval.</p>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Confirm Cash Payment
                                </button>
                            </div>
                        </form>

                        <div class="mt-4">
                            <p class="text-muted">Or</p>
                            <a href="<?php echo BASE_URL; ?>/payment/receipt/<?= $permit['id'] ?>" class="btn btn-secondary">
                                <i class="fas fa-download"></i> Generate PDF Receipt (Option B)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R&currency=USD"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paypalRadio = document.getElementById('paypal');
    const cashRadio = document.getElementById('cash');
    const paypalSection = document.getElementById('paypal-section');
    const cashSection = document.getElementById('cash-section');

    function togglePaymentSections() {
        if (paypalRadio.checked) {
            paypalSection.style.display = 'block';
            cashSection.style.display = 'none';
        } else if (cashRadio.checked) {
            paypalSection.style.display = 'none';
            cashSection.style.display = 'block';
        }
    }

    paypalRadio.addEventListener('change', function() {
        document.getElementById('payment_type').value = 'online';
        togglePaymentSections();
    });
    cashRadio.addEventListener('change', function() {
        document.getElementById('payment_type').value = 'cash_on_pickup';
        togglePaymentSections();
    });

    paypal.Buttons({
        createOrder: function(data, actions) {
            return fetch('<?php echo BASE_URL; ?>/payment/create', {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    permit_id: <?php echo $permit['id']; ?>,
                    amount: <?php echo $amount; ?>
                })
            }).then(function(res) {
                return res.json();
            }).then(function(data) {
                return data.id;
            });
        },
        onApprove: function(data, actions) {
            window.location.href = '<?php echo BASE_URL; ?>/payment/approve?token=' + data.orderID;
        },
        onCancel: function(data) {
            window.location.href = '<?php echo BASE_URL; ?>/dashboard';
        }
    }).render('#paypal-button-container');
});
</script>

<?php include_once(APP_DIR . 'views/includes/footer.php'); ?>
