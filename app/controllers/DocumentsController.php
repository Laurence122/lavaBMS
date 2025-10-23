<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DocumentsController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
        $this->call->model('DocumentsModel');
        $this->call->model('NotificationModel');
        $this->call->model('UserModel');
    }

    public function index()
    {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $logged_in_user = [
            'id' => $this->session->userdata('id'),
            'username' => $this->session->userdata('username'),
            'role' => $this->session->userdata('role')
        ];
        $data['logged_in_user'] = $logged_in_user;

        $data['documents'] = $this->DocumentsModel->get_all_documents();

        $this->call->view('documents/index', $data);
    }

    public function request()
    {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        // Check citizen verification status
        $this->call->model('CitizensModel');
        if (!$this->CitizensModel->is_verified($this->session->userdata('id'))) {
            $data['error'] = 'You must be verified with your National ID before requesting documents.';
            $logged_in_user = [
                'id' => $this->session->userdata('id'),
                'username' => $this->session->userdata('username'),
                'role' => $this->session->userdata('role')
            ];
            $data['logged_in_user'] = $logged_in_user;
            $this->call->view('documents/request', $data);
            return;
        }

        if ($this->io->method() === 'post') {
            $national_id = $this->io->post('national_id');

            // Validate National ID
            if (empty($national_id)) {
                $data['error'] = 'National ID is required.';
                $logged_in_user = [
                    'id' => $this->session->userdata('id'),
                    'username' => $this->session->userdata('username'),
                    'role' => $this->session->userdata('role')
                ];
                $data['logged_in_user'] = $logged_in_user;
                $this->call->view('documents/request', $data);
                return;
            }

            // Verify National ID
            if (!$this->CitizensModel->verify_national_id($this->session->userdata('id'), $national_id)) {
                $data['error'] = 'Invalid National ID. Please check your National ID and try again.';
                $logged_in_user = [
                    'id' => $this->session->userdata('id'),
                    'username' => $this->session->userdata('username'),
                    'role' => $this->session->userdata('role')
                ];
                $data['logged_in_user'] = $logged_in_user;
                $this->call->view('documents/request', $data);
                return;
            }

            // Set fee based on document type
            $fee = 50.00; // Default fee
            $document_type = $this->io->post('document_type');
            if ($document_type === 'certificate_of_indigency') {
                $fee = 25.00;
            } elseif ($document_type === 'clearance') {
                $fee = 75.00;
            } elseif ($document_type === 'residency_certificate') {
                $fee = 50.00;
            }

            $data = [
                'user_id' => $this->session->userdata('id'),
                'document_type' => $document_type,
                'purpose' => $this->io->post('purpose'),
                'fee' => $fee,
                'status' => 'pending',
                'requested_at' => date('Y-m-d H:i:s')
            ];

            if ($this->DocumentsModel->insert($data)) {
                $document_id = $this->db->last_id(); // Get the newly inserted document ID
                $staff_and_admins = $this->UserModel->get_staff_and_admins();
                foreach ($staff_and_admins as $user) {
                    $this->NotificationModel->create_notification($user['id'], 'A new document has been requested.');
                }
                redirect('/documents/payment/' . $document_id);
            } else {
                $data['error'] = 'Failed to submit document request.';
                $logged_in_user = [
                    'id' => $this->session->userdata('id'),
                    'username' => $this->session->userdata('username'),
                    'role' => $this->session->userdata('role')
                ];
                $data['logged_in_user'] = $logged_in_user;
                $this->call->view('documents/request', $data);
            }
        } else {
            $logged_in_user = [
                'id' => $this->session->userdata('id'),
                'username' => $this->session->userdata('username'),
                'role' => $this->session->userdata('role')
            ];
            $data['logged_in_user'] = $logged_in_user;
            $this->call->view('documents/request', $data);
        }
    }

    public function update_status($id)
    {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        $status = $this->io->get('status');

        if ($status && in_array($status, ['approved', 'rejected', 'pending'])) {
            $update_data = [
                'status' => $status
            ];

            if ($status === 'approved') {
                // Set status to approved_pending_payment to require payment
                $update_data['status'] = 'approved_pending_payment';
                // Processed at will be set after payment
            }

            if ($this->DocumentsModel->update($id, $update_data)) {
                $document = $this->DocumentsModel->get_document_by_id($id);

                // Get user email for notifications
                $user = $this->UserModel->get_user_by_id($document['user_id']);

                if ($status === 'approved') {
                    $this->NotificationModel->create_notification($document['user_id'], 'Your document request has been approved but requires payment before final approval. Please complete your payment to proceed.');
                    if ($user && isset($user['email'])) {
                        $this->NotificationModel->send_document_approval_email($user['email'], $document['document_type'], 'approved');
                    }
                } elseif ($status === 'rejected') {
                    $this->NotificationModel->create_notification($document['user_id'], 'Your document request has been rejected. Please contact the barangay office for more information.');
                    if ($user && isset($user['email'])) {
                        $this->NotificationModel->send_document_approval_email($user['email'], $document['document_type'], 'rejected');
                    }
                }
                redirect('/documents');
            } else {
                echo 'Failed to update document status.';
            }
        } else {
            redirect('/documents');
        }
    }

    public function set_pickup_time($id)
    {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        if ($this->io->method() === 'post') {
            $pickup_time = $this->io->post('pickup_time');

            if ($pickup_time) {
                $update_data = [
                    'pickup_time' => $pickup_time
                ];

                if ($this->DocumentsModel->update($id, $update_data)) {
                    $document = $this->DocumentsModel->get_document_by_id($id);
                    $this->NotificationModel->create_notification($document['user_id'], 'Your document pickup time has been scheduled for ' . date('F d, Y H:i', strtotime($pickup_time)) . '. Please arrive at the barangay hall at the specified time.');
                    echo json_encode(['success' => true]);
                    exit;
                }
            }
        }

        echo json_encode(['success' => false]);
        exit;
    }

    public function payment($id)
    {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $document = $this->DocumentsModel->get_document_by_id($id);

        if (!$document || $document['user_id'] != $this->session->userdata('id')) {
            redirect('/dashboard');
            exit;
        }

        // Allow payment for pending documents (after request submission)
        if ($document['status'] !== 'pending' && $document['status'] !== 'approved_pending_payment') {
            redirect('/dashboard');
            exit;
        }

        $data['document'] = $document;
        $this->call->view('documents/payment', $data);
    }

    public function download($id)
    {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $document = $this->DocumentsModel->get_document_by_id($id);

        if (!$document || $document['user_id'] != $this->session->userdata('id') || $document['status'] != 'approved') {
            redirect('/dashboard');
            exit;
        }

        // Get citizen profile information
        $this->call->model('CitizensModel');
        $citizen_profile = $this->CitizensModel->get_citizen_by_user_id($document['user_id']);

        // Add citizen name to document data for PDF generation
        $document['citizen_name'] = $citizen_profile ? ($citizen_profile['first_name'] . ' ' . $citizen_profile['last_name']) : $document['username'];

        $this->call->library('PdfGenerator');
        $pdf_content = $this->PdfGenerator->generate_document_certificate($document);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="document_certificate_' . $id . '.pdf"');
        echo $pdf_content;
        exit;
    }

    public function set_fee()
    {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        if ($this->io->method() === 'post') {
            $document_id = $this->io->post('document_id');
            $fee = $this->io->post('fee');

            if ($document_id && is_numeric($fee) && $fee >= 0) {
                // Update the document fee and status
                $update_data = [
                    'fee' => $fee,
                    'status' => 'approved_pending_payment'
                ];

                if ($this->DocumentsModel->update($document_id, $update_data)) {
                    // Get document details for notification
                    $document = $this->DocumentsModel->get_document_by_id($document_id);

                    // Notify the citizen
                    $this->NotificationModel->create_notification($document['user_id'], 'Your document request has been approved. Please proceed with payment.');

                    echo json_encode(['success' => true]);
                    exit;
                }
            }
        }

        echo json_encode(['success' => false]);
        exit;
    }

    public function generate_certificate($id)
    {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        $this->call->library('PdfGenerator');
        $document = $this->DocumentsModel->get_document_by_id($id);

        if ($document && $document['status'] == 'paid') {
            $pdf_content = $this->PdfGenerator->generate_document_certificate($document);

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="document_certificate_' . $id . '.pdf"');
            echo $pdf_content;
            exit;
        } else {
            echo 'Certificate cannot be generated for this document.';
        }
    }

    public function cancel($id)
    {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $document = $this->DocumentsModel->get_document_by_id($id);

        if (!$document || $document['user_id'] != $this->session->userdata('id')) {
            redirect('/dashboard');
            exit;
        }

        // Only allow cancellation for pending documents
        if ($document['status'] !== 'pending') {
            redirect('/dashboard');
            exit;
        }

        // Update status to cancelled
        $update_data = [
            'status' => 'cancelled'
        ];

        if ($this->DocumentsModel->update($id, $update_data)) {
            // Notify the user
            $this->NotificationModel->create_notification($document['user_id'], 'Your document request has been cancelled.');
            redirect('/dashboard');
        } else {
            echo 'Failed to cancel document request.';
        }
    }
}
