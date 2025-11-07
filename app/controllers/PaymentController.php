<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

require_once APP_DIR . '../vendor/autoload.php';

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PaymentController extends Controller {

    private $client;

    public function __construct() {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
        $this->call->model('PermitsModel');
        $this->call->model('PaymentModel');

        // Suppress PayPal SDK deprecation warnings for PHP 8.2 compatibility
        $original_error_reporting = error_reporting(E_ALL & ~E_DEPRECATED);

        // PayPal configuration - Using valid sandbox credentials
        $clientId = getenv('PAYPAL_CLIENT_ID') ?: 'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R'; // Sandbox client ID
        $clientSecret = getenv('PAYPAL_CLIENT_SECRET') ?: 'EMaG5rQfKv9F7L3QF1oYzYwCfWKz6uG6KjJcWb8qLxEzBJVzHqEb9qXHjKs5qE8qEB5qWb8qLxEzBJVz'; // Sandbox secret

        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $this->client = new PayPalHttpClient($environment);

        // Restore original error reporting
        error_reporting($original_error_reporting);
    }

    public function create() {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $permit_id = $this->io->post('permit_id');
        $document_id = $this->io->post('document_id');
        $paypal_order_id = $this->io->post('paypal_order_id');
        $paypal_payer_id = $this->io->post('paypal_payer_id');

        // Get document fee from database if document_id is provided
        $amount = $this->io->post('amount');
        if (!$amount && $document_id) {
            $this->call->model('DocumentsModel');
            $document = $this->DocumentsModel->get_document_by_id($document_id);
            $amount = $document ? $document['fee'] : 50.00;
        } elseif (!$amount && $permit_id) {
            $amount = 500.00; // Default permit fee
        }
        $payment_method = $this->io->post('payment_method') ?: 'paypal';
        $payment_type = $this->io->post('payment_type') ?: 'online';

        // Handle PayPal sandbox payment completion
        if ($paypal_order_id && $paypal_payer_id) {
            // Store payment record for PayPal
            $payment_data = [
                'user_id' => $this->session->userdata('id'),
                'permit_id' => $permit_id,
                'document_id' => $document_id,
                'amount' => $amount,
                'payment_method' => 'paypal',
                'payment_type' => 'online',
                'paypal_order_id' => $paypal_order_id,
                'status' => 'completed',
                'transaction_id' => $paypal_order_id, // Using order_id as transaction_id for sandbox
                'description' => $document_id ? 'Document Processing Fee - PayPal Sandbox' : 'Business Permit Application Fee - PayPal Sandbox'
            ];

            if ($this->PaymentModel->insert($payment_data)) {
                if ($permit_id) {
                    $this->PermitsModel->update($permit_id, ['status' => 'paid']);
                    $this->session->set_flashdata('success', 'PayPal payment completed successfully! Your permit application is now being processed.');
                } elseif ($document_id) {
                    $this->call->model('DocumentsModel');
                    $this->DocumentsModel->update($document_id, ['status' => 'paid']);
                    $this->session->set_flashdata('success', 'PayPal payment completed successfully! Your document is now ready for download.');
                }
                redirect('/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Failed to process PayPal payment.');
                if ($permit_id) {
                    redirect('/permits/payment/' . $permit_id);
                } elseif ($document_id) {
                    redirect('/documents/payment/' . $document_id);
                }
            }
            exit;
        }

        if (!$permit_id && !$document_id) {
            $this->session->set_flashdata('error', 'Permit ID or Document ID is required');
            redirect('/permits/apply');
            exit;
        }

        if ($payment_method === 'cash') {
            // Handle cash payment
            $payment_data = [
                'user_id' => $this->session->userdata('id'),
                'permit_id' => $permit_id,
                'document_id' => $document_id,
                'amount' => $amount,
                'payment_method' => 'cash',
                'payment_type' => 'cash_on_pickup',
                'status' => 'pending',
                'description' => $document_id ? 'Document Processing Fee - Cash on Pickup' : 'Business Permit Application Fee - Cash on Pickup'
            ];

            if ($this->PaymentModel->insert($payment_data)) {
                if ($permit_id) {
                    // Update permit status to paid for cash payment
                    $this->PermitsModel->update($permit_id, ['status' => 'paid']);
                    $this->session->set_flashdata('success', 'Cash payment selected. You will pay when you pick up your permit after approval.');
                } elseif ($document_id) {
                    // Update document status to paid for cash payment
                    $this->call->model('DocumentsModel');
                    $this->DocumentsModel->update($document_id, ['status' => 'paid']);
                    $this->session->set_flashdata('success', 'Cash payment selected. You will pay when you pick up your document.');
                }
                redirect('/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Failed to process cash payment selection.');
                if ($permit_id) {
                    redirect('/permits/payment/' . $permit_id);
                } elseif ($document_id) {
                    redirect('/documents/payment/' . $document_id);
                }
            }
            exit;
        }

        // Handle PayPal payment - return JSON for frontend
        $reference_id = $permit_id ? "permit_" . $permit_id : "document_" . $document_id;
        $description = $document_id ? "Document Processing Fee" : "Business Permit Application Fee";

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $reference_id,
                "amount" => [
                    "value" => number_format($amount, 2, '.', ''),
                    "currency_code" => $document_id ? "PHP" : "USD"
                ],
                "description" => $description
            ]],
            "application_context" => [
                "cancel_url" => site_url('payment/cancel'),
                "return_url" => site_url('payment/approve')
            ]
        ];

        try {
            $response = $this->client->execute($request);
            $order = $response->result;

            // Store payment record
            $payment_data = [
                'user_id' => $this->session->userdata('id'),
                'permit_id' => $permit_id,
                'document_id' => $document_id,
                'amount' => $amount,
                'payment_method' => 'paypal',
                'payment_type' => 'online',
                'paypal_order_id' => $order->id,
                'status' => 'pending',
                'description' => $description
            ];

            $this->PaymentModel->insert($payment_data);

            // Return order ID for frontend
            header('Content-Type: application/json');
            echo json_encode(['id' => $order->id]);
            exit;

        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Payment creation failed: ' . $e->getMessage()]);
            exit;
        }
    }

    public function approve() {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $order_id = $this->io->post('orderID') ?: $this->io->get('token');

        if (!$order_id) {
            $this->session->set_flashdata('error', 'Invalid payment token');
            redirect('/dashboard');
            exit;
        }

        // Fake PayPal capture for mod/demo purposes - always succeed
        try {
            // Simulate successful payment
            $this->PaymentModel->update_by_paypal_order_id($order_id, [
                'status' => 'completed',
                'transaction_id' => 'FAKE-' . $order_id
            ]);

            // Update permit or document status
            $payment = $this->PaymentModel->get_by_paypal_order_id($order_id);
            if ($payment) {
                if ($payment['permit_id']) {
                    $this->PermitsModel->update($payment['permit_id'], [
                        'status' => 'paid'
                    ]);
                    $this->session->set_flashdata('success', 'Payment completed successfully! Your permit application is now being processed.');
                    // Redirect to dashboard
                    redirect('/dashboard');
                } elseif ($payment['document_id']) {
                    $this->call->model('DocumentsModel');
                    $this->DocumentsModel->update($payment['document_id'], ['status' => 'paid']);
                    $this->session->set_flashdata('success', 'Payment completed successfully! Your document is now ready for download.');
                    // Redirect to dashboard
                    redirect('/dashboard');
                }
            }

        } catch (Exception $e) {
            $this->PaymentModel->update_by_paypal_order_id($order_id, ['status' => 'failed']);
            $this->session->set_flashdata('error', 'Payment processing failed: ' . $e->getMessage());
        }

        redirect('/dashboard');
    }

    public function cancel() {
        $this->session->set_flashdata('error', 'Payment was cancelled.');
        redirect('/dashboard');
    }

    public function generate_receipt($payment_id) {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $this->call->library('PdfGenerator');
        $this->call->model('PaymentModel');

        $payment = $this->PaymentModel->get_by_id($payment_id);

        if (!$payment || $payment['user_id'] != $this->session->userdata('id')) {
            show_error('Payment not found or access denied');
        }

        $receipt_data = [
            'receipt_no' => 'RCP-' . $payment_id,
            'paid_by' => $this->session->userdata('username'),
            'amount' => $payment['amount'],
            'description' => $payment['description'],
            'payment_type' => $payment['payment_type'] ?? 'online'
        ];

        $pdf_content = $this->PdfGenerator->generate_payment_receipt($receipt_data);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="payment_receipt_' . $payment_id . '.pdf"');
        echo $pdf_content;
        exit;
    }
}
