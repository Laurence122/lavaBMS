<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DashboardController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
        $this->call->model('CitizensModel');
    }

    public function index() {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $role = $this->session->userdata('role');
        $logged_in_user = [
            'id' => $this->session->userdata('id'),
            'username' => $this->session->userdata('username'),
            'role' => $role
        ];
        $data['logged_in_user'] = $logged_in_user;

        if ($role === 'admin') {
            $this->call->model('DocumentsModel');
            $this->call->model('PermitsModel');
            $this->call->model('StaffModel');
            
            // Get document and permit data
            $document_type_distribution = $this->DocumentsModel->get_document_type_distribution();
            $data['document_type_distribution'] = json_encode($document_type_distribution);
            $data['all_documents'] = $this->DocumentsModel->get_all_documents();
            $data['all_permits'] = $this->PermitsModel->get_all_permits();
            $data['staff_tasks'] = $this->StaffModel->get_all_tasks_with_staff_names();
            
            // Get citizen statistics
            $data['total_citizens'] = $this->CitizensModel->get_total_citizens_count();
            $data['pending_verifications'] = $this->CitizensModel->get_pending_verifications_count();
            
            $this->call->view('dashboard/admin', $data);
        } elseif ($role === 'staff') {
            $this->call->model('DocumentsModel');
            $this->call->model('PermitsModel');
            $data['pending_documents'] = $this->DocumentsModel->get_pending_documents();
            $data['pending_permits'] = $this->PermitsModel->get_pending_permits();
            $data['paid_documents'] = $this->DocumentsModel->get_documents_by_status('paid');
            $data['paid_permits'] = $this->PermitsModel->get_permits_by_status('paid');
            $this->call->view('dashboard/staff', $data);
        } elseif ($role === 'citizen') {
            $this->call->model('DocumentsModel');
            $this->call->model('PermitsModel');
            $data['my_documents'] = $this->DocumentsModel->get_documents_by_user($logged_in_user['id']);
            $data['my_permits'] = $this->PermitsModel->get_permits_by_user($logged_in_user['id']);
            
            $citizen_profile = $this->CitizensModel->get_citizen_by_user_id($logged_in_user['id']);
            $data['citizen_profile'] = $citizen_profile;
            
            $this->call->view('dashboard/citizen', $data);
        } else {
            $this->call->view('dashboard/index', $data);
        }
    }

    public function check_new_requests() {
        if (!$this->auth->is_logged_in() || !$this->auth->is_staff()) {
            $this->output->set_status_header(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }

        $this->call->model('DocumentsModel');
        $this->call->model('PermitsModel');

        $pending_documents_count = count($this->DocumentsModel->get_pending_documents());
        $pending_permits_count = count($this->PermitsModel->get_pending_permits());

        $this->output->set_content_type('application/json');
        echo json_encode([
            'pending_documents' => $pending_documents_count,
            'pending_permits' => $pending_permits_count
        ]);
    }
}
