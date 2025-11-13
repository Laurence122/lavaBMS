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

          <button type="button" id="pay-button" class="btn btn-primary" disabled>Pay with PayPal</button>
        </form>

        <!-- hidden PayPal render container -->
        <div id="paypal-button-container" style="display:none;"></div>

        <!-- show PayPal errors to user when they happen -->
        <div id="paypal-error" class="text-danger text-center mt-3" style="display:none;"></div>

      </div>
    </div>
  </div>

  <!-- PayPal SDK -->
  <script src="https://www.paypal.com/sdk/js?client-id=AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R&currency=PHP"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <script>
    $(function() {
      var $form = $('#payment-form');
      var $hiddenMethod = $('#payment_method');
      var $payBtn = $('#pay-button');
      var $options = $('.payment-option');
      var $paypalError = $('#paypal-error');

      // formatted amount as string (2 decimals)
      var amountValue = '<?= isset($document['fee']) ? number_format($document['fee'], 2, '.', '') : '50.00' ?>';

      // set initial method from active tile
      var $active = $options.filter('.active').first();
      if ($active.length) $hiddenMethod.val($active.data('method'));

      $options.on('click', function() {
        $options.removeClass('active');
        $(this).addClass('active');
        var method = $(this).data('method');
        $hiddenMethod.val(method);
        $payBtn.text(method === 'paypal' ? 'Pay with PayPal' : 'Proceed with Cash');

        // enable/disable visible pay button for PayPal until SDK is ready
        if (method === 'paypal') {
          if (window.paypalReady) {
            $payBtn.prop('disabled', false);
          } else {
            $payBtn.prop('disabled', true);
          }
        } else {
          $payBtn.prop('disabled', false);
        }
      });

      // create PayPal Buttons and keep reference available globally
      window.paypalReady = false;
      window.paypalButtons = paypal.Buttons({
        style: { layout: 'vertical' },
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: amountValue,
                currency_code: 'PHP'
              }
            }]
          });
        },
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(details) {
            $('<input>').attr({ type: 'hidden', name: 'paypal_order_id', value: data.orderID }).appendTo($form);
            $('<input>').attr({ type: 'hidden', name: 'paypal_payer_id', value: details.payer.payer_id }).appendTo($form);
            $form.submit();
          });
        },
        onError: function(err) {
          // show more helpful info for debugging
          console.error('PayPal onError:', err);
          var msg = 'PayPal Payment Failed. ' + (err && err.message ? err.message : 'Please try again.');
          $paypalError.text(msg).show();
          alert(msg);
        }
      });

      // render into hidden container (required so .click() works)
      window.paypalButtons.render('#paypal-button-container')
        .then(function() {
          window.paypalReady = true;
          // if PayPal currently selected, enable visible pay button
          if ($hiddenMethod.val() === 'paypal') $payBtn.prop('disabled', false);
        })
        .catch(function(err){
          // rendering failed (invalid client-id, network, or SDK issue)
          window.paypalReady = false;
          console.error('PayPal render error:', err);
          $paypalError.text('PayPal initialization failed. Check console for details.').show();
          // allow fallback server-side submit if needed
          if ($hiddenMethod.val() !== 'paypal') $payBtn.prop('disabled', false);
        });

      // visible button handler: trigger PayPal flow or submit for cash
      $payBtn.on('click', function() {
        var method = $hiddenMethod.val();
        if (method === 'paypal') {
          if (window.paypalReady && window.paypalButtons && typeof window.paypalButtons.click === 'function') {
            window.paypalButtons.click();
          } else {
            // fallback: submit to server to start server-side flow
            $form.submit();
          }
        } else {
          // cash or other methods - submit form to your cash handler
          $form.submit();
        }
      });
    });
  </script>
</body>
</html>
