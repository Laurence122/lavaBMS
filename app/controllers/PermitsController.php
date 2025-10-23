<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class PermitsController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
        $this->call->model('PermitsModel');
        $this->call->model('NotificationModel');
        $this->call->model('UserModel');
    }

    public function index() {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        $logged_in_user = [
            'id' => $this->session->userdata('id'),
            'username' => $this->session->userdata('username'),
            'role' => $this->session->userdata('role')
        ];
        $data['logged_in_user'] = $logged_in_user;

        $q = '';
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $q = trim($this->io->get('q'));
        }

        $permits_data = $this->PermitsModel->page($q);

        $data['permits'] = $permits_data['records'];

        $this->call->view('permits/index', $data);
    }

    public function apply() {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        // Check citizen verification status
        $this->call->model('CitizensModel');
        if (!$this->CitizensModel->is_verified($this->session->userdata('id'))) {
            $data['error'] = 'You must be verified with your National ID before applying for permits.';
            $this->call->view('permits/apply', $data);
            return;
        }

        if ($this->io->method() === 'post') {
            $data = [
                'user_id' => $this->session->userdata('id'),
                'business_name' => $this->io->post('business_name'),
                'business_address' => $this->io->post('business_address'),
                'owner_name' => $this->io->post('owner_name'),
                'status' => 'pending_payment',
                'applied_at' => date('Y-m-d H:i:s')
            ];

            if ($this->PermitsModel->insert($data)) {
                $staff_and_admins = $this->UserModel->get_staff_and_admins();
                foreach ($staff_and_admins as $user) {
                    $this->NotificationModel->create_notification($user['id'], 'A new business permit has been applied for.');
                }
                // Redirect to payment instead of dashboard
                redirect('/permits/payment/' . $this->db->last_id());
            } else {
                $data['error'] = 'Failed to submit permit application.';
                $logged_in_user = [
                    'id' => $this->session->userdata('id'),
                    'username' => $this->session->userdata('username'),
                    'role' => $this->session->userdata('role')
                ];
                $data['logged_in_user'] = $logged_in_user;
                $this->call->view('permits/apply', $data);
            }
        } else {
            $logged_in_user = [
                'id' => $this->session->userdata('id'),
                'username' => $this->session->userdata('username'),
                'role' => $this->session->userdata('role')
            ];
            $data['logged_in_user'] = $logged_in_user;
            $this->call->view('permits/apply', $data);
        }
    }

    public function payment($permit_id) {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $permit = $this->PermitsModel->get_permit_by_id($permit_id);

        if (!$permit || $permit['user_id'] != $this->session->userdata('id')) {
            redirect('/dashboard');
            exit;
        }

        // Check if payment already exists for this permit
        $this->call->model('PaymentModel');
        $existing_payment = $this->PaymentModel->get_by_permit_id($permit_id);
        if ($existing_payment) {
            $this->session->set_flashdata('info', 'Payment has already been processed for this permit.');
            redirect('/dashboard');
            exit;
        }

        $data['permit'] = $permit;
        $data['amount'] = 500.00; // Business permit fee

        $this->call->view('permits/payment', $data);
    }

    public function update_status($id) {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        $status = $this->io->get('status');

        if ($status && in_array($status, ['approved', 'rejected', 'pending_inspection', 'pending_payment'])) {
            $update_data = [
                'status' => $status
            ];

            if ($status === 'approved') {
                $update_data['claim_by_date'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $update_data['processed_at'] = date('Y-m-d H:i:s');
            }

            if ($this->PermitsModel->update($id, $update_data)) {
                $permit = $this->PermitsModel->get_permit_by_id($id);

                // Get user email for notifications
                $user = $this->UserModel->get_user_by_id($permit['user_id']);

                if ($status === 'approved') {
                    $this->NotificationModel->create_notification($permit['user_id'], 'Your business permit has been approved. Claim by: ' . date('M d, Y, g:i A', strtotime($update_data['claim_by_date'])));
                    if ($user && isset($user['email'])) {
                        $this->NotificationModel->send_permit_approval_email($user['email'], $permit['business_name'], 'approved');
                    }
                } elseif ($status === 'rejected') {
                    $this->NotificationModel->create_notification($permit['user_id'], 'Your business permit application has been rejected.');
                    if ($user && isset($user['email'])) {
                        $this->NotificationModel->send_permit_approval_email($user['email'], $permit['business_name'], 'rejected');
                    }
                }
                redirect('/dashboard');
            } else {
                echo 'Failed to update permit status.';
            }
        } else {
            redirect('/dashboard');
        }
    }

    public function generate_certificate($id) {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        $this->call->library('PdfGenerator');
        $permit = $this->PermitsModel->get_permit_by_id($id);

        if ($permit && $permit['status'] == 'approved') {
            $pdf_content = $this->PdfGenerator->generate_permit_certificate($permit);

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="business_permit_' . $id . '.pdf"');
            echo $pdf_content;
            exit;
        } else {
            echo 'Certificate cannot be generated for this permit.';
        }
    }
}
