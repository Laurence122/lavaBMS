<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document Payment · Barangay Management System</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .payment-card {
      max-width: 600px;
      margin: 40px auto;
      border: none;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, .05);
    }

    .card-header {
      text-align: center;
      background: linear-gradient(45deg, #007bff, #4facfe);
      color: #fff;
      font-size: 1.3rem;
      font-weight: 500;
    }

    .payment-amount {
      font-size: 2rem;
      font-weight: bold;
      color: #007bff;
      text-align: center;
      margin: 20px 0;
    }

    .payment-options {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 30px 0;
    }

    .payment-option {
      text-align: center;
      padding: 20px;
      border: 2px solid #dee2e6;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s;
      flex: 1;
    }

    .payment-option:hover,
    .payment-option.active {
      border-color: #007bff;
      background-color: #f8f9ff;
    }

    .payment-option i {
      font-size: 2rem;
      margin-bottom: 10px;
      color: #007bff;
    }

    .paypal-btn {
      background-color: #0070ba;
      color: #fff;
      font-size: 1rem;
      font-weight: 600;
      padding: 12px 25px;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background-color 0.3s;
    }

    .paypal-btn:hover {
      background-color: #005ea6;
    }

    .paypal-btn i {
      font-size: 1.3rem;
    }

    #paypal-button-container {
      display: block;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card payment-card">
      <div class="card-header">
        Document Payment
      </div>
      <div class="card-body">
        <div class="text-center mb-4">
          <h5>Document Type: <?= ucfirst(str_replace('_', ' ', $document['document_type'])) ?></h5>
          <p class="text-muted">Purpose: <?= htmlspecialchars($document['purpose']) ?></p>
        </div>

        <div class="payment-amount">
          PHP <?= isset($document['fee']) ? number_format($document['fee'], 2) : '50.00' ?>
        </div>

        <form id="payment-form" action="<?php echo BASE_URL; ?>/payment/create" method="post">
          <input type="hidden" name="document_id" value="<?= $document['id'] ?>">
          <input type="hidden" name="amount" value="<?= isset($document['fee']) ? $document['fee'] : 50.00 ?>">

          <!-- hidden input to store selection -->
          <input type="hidden" id="payment_method" name="payment_method" value="paypal">
          <input type="hidden" id="payment_type" name="payment_type" value="online">

          <div class="payment-options">
            <button type="button" class="payment-option btn btn-outline-primary active" data-method="paypal">
              <i class="fab fa-paypal"></i>
              <div>PayPal</div>
            </button>

            <button type="button" class="payment-option btn btn-outline-secondary" data-method="cash">
              <i class="fas fa-money-bill-wave"></i>
              <div>Cash on Pickup</div>
            </button>
          </div>

          <button type="button" id="pay-button" class="btn btn-primary">Pay with PayPal</button>
        </form>

        <!-- PayPal render container -->
        <div id="paypal-button-container"></div>

        <!-- show PayPal errors to user when they happen -->
        <div id="paypal-error" class="text-danger text-center mt-3" style="display:none;"></div>

      </div>
    </div>
  </div>

  <!-- PayPal SDK -->
  <script src="https://www.paypal.com/sdk/js?client-id=AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R&currency=PHP"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const paypalOption = document.querySelector('.payment-option[data-method="paypal"]');
      const cashOption = document.querySelector('.payment-option[data-method="cash"]');
      const paypalSection = document.getElementById('paypal-section') || document.createElement('div');
      const cashSection = document.getElementById('cash-section') || document.createElement('div');
      const payBtn = document.getElementById('pay-button');
      const form = document.getElementById('payment-form');
      const hiddenMethod = document.getElementById('payment_method');

      // Create sections if they don't exist
      if (!document.getElementById('paypal-section')) {
        paypalSection.id = 'paypal-section';
        paypalSection.className = 'mt-4';
        paypalSection.innerHTML = '<p class="text-center mb-2">Amount to pay: PHP <?php echo isset($document['fee']) ? number_format($document['fee'], 2) : '50.00'; ?></p><div id="paypal-button-container"></div>';
        form.appendChild(paypalSection);
      }
      if (!document.getElementById('cash-section')) {
        cashSection.id = 'cash-section';
        cashSection.className = 'mt-4';
        cashSection.style.display = 'none';
        cashSection.innerHTML = '<p>You have selected Cash on Pickup. You will pay the fee when you pick up your document.</p><button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Confirm Cash Payment</button>';
        form.appendChild(cashSection);
      }

      function togglePaymentSections() {
        if (hiddenMethod.value === 'paypal') {
          paypalSection.style.display = 'block';
          cashSection.style.display = 'none';
        } else if (hiddenMethod.value === 'cash') {
          paypalSection.style.display = 'none';
          cashSection.style.display = 'block';
        }
      }

      // Set initial method
      hiddenMethod.value = 'paypal';
      togglePaymentSections();

      // Handle option clicks
      document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
          document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
          this.classList.add('active');
          hiddenMethod.value = this.dataset.method;
          document.getElementById('payment_type').value = this.dataset.method === 'paypal' ? 'online' : 'cash_on_pickup';
          payBtn.textContent = this.dataset.method === 'paypal' ? 'Pay with PayPal' : 'Proceed with Cash';
          togglePaymentSections();
        });
      });

      // PayPal Buttons (Demo Mod - No real redirect)
      paypal.Buttons({
        createOrder: function(data, actions) {
          // Simulate order creation without API call
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: '<?php echo isset($document['fee']) ? $document['fee'] : 50.00; ?>',
                currency_code: 'PHP'
              },
              description: 'Document Processing Fee - Demo'
            }]
          });
        },
        onApprove: function(data, actions) {
          // Simulate successful payment approval
          window.location.href = '<?php echo BASE_URL; ?>/payment/approve?token=' + data.orderID;
        },
        onCancel: function(data) {
          window.location.href = '<?php echo BASE_URL; ?>/dashboard';
        }
      }).render('#paypal-button-container');
    });
  </script>
</body>
</html>
