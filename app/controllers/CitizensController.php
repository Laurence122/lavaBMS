<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CitizensController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
        $this->call->model('CitizensModel');
    }

    public function index()
    {
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

        $citizens_data = $this->CitizensModel->page($q);

        $data['citizens'] = $citizens_data['records'];

        $this->call->view('citizens/index', $data);
    }

    public function create()
    {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        if ($this->io->method() === 'post') {
            $data = [
                'user_id' => $this->io->post('user_id'),
                'first_name' => $this->io->post('first_name'),
                'last_name' => $this->io->post('last_name'),
                'middle_name' => $this->io->post('middle_name'),
                'email' => $this->io->post('email'),
                'phone' => $this->io->post('phone'),
                'address' => $this->io->post('address'),
                'birth_date' => $this->io->post('birth_date'),
                'gender' => $this->io->post('gender'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->CitizensModel->insert($data)) {
                redirect('/citizens');
            } else {
                $data['error'] = 'Failed to create citizen record.';
                $this->call->view('citizens/create', $data);
            }
        } else {
            $this->call->view('citizens/create');
        }
    }

    public function update($id)
    {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        $citizen = $this->CitizensModel->get_citizen_by_id($id);
        if (!$citizen) {
            echo "Citizen not found.";
            return;
        }

        if ($this->io->method() === 'post') {
            $national_id = $this->io->post('national_id');

            $update_data = [
                'first_name' => $this->io->post('first_name'),
                'last_name' => $this->io->post('last_name'),
                'middle_name' => $this->io->post('middle_name'),
                'email' => $this->io->post('email'),
                'phone' => $this->io->post('phone'),
                'address' => $this->io->post('address'),
                'birth_date' => $this->io->post('birth_date'),
                'gender' => $this->io->post('gender'),
                'national_id' => $national_id
            ];

            // If national_id is provided and citizen exists, update verification status
            if (!empty($national_id) && $citizen) {
                $update_data['verification_status'] = 'verified';
                $update_data['verified_at'] = date('Y-m-d H:i:s');
            }

            if ($this->CitizensModel->update($id, $update_data)) {
                redirect('/citizens');
            } else {
                echo 'Failed to update citizen.';
            }
        } else {
            $data['citizen'] = $citizen;
            $this->call->view('citizens/update', $data);
        }
    }

    public function delete($id)
    {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        $this->CitizensModel->delete($id);
        redirect('/citizens');
    }

    public function verifications()
    {
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

        // Get citizens with pending verification status and national_id provided
        $citizens = $this->CitizensModel->db->table('citizens')
            ->where_in('verification_status', ['pending', null])
            ->where('national_id IS NOT NULL', null, false)
            ->where('national_id !=', '')
            ->get_all();

        $data['citizens'] = $citizens;

        $this->call->view('citizens/verifications', $data);
    }

    public function verify($id)
    {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        $citizen = $this->CitizensModel->get_citizen_by_id($id);

        if (!$citizen) {
            redirect('/citizens/verifications');
            exit;
        }

        if ($this->io->method() === 'post') {
            $verification_action = $this->io->post('verification_action');

            if ($verification_action === 'verify') {
                $this->CitizensModel->update_verification_status($citizen['user_id'], 'verified', $citizen['national_id']);
                // Send notification to citizen
                $this->call->model('NotificationModel');
                $this->NotificationModel->create_notification($citizen['user_id'], 'Your National ID has been verified successfully.');
            } elseif ($verification_action === 'reject') {
                $this->CitizensModel->update_verification_status($citizen['user_id'], 'rejected');
                // Send notification to citizen
                $this->call->model('NotificationModel');
                $this->NotificationModel->create_notification($citizen['user_id'], 'Your National ID verification has been rejected. Please check your information and try again.');
            }

            redirect('/citizens/verifications');
        } else {
            $logged_in_user = [
                'id' => $this->session->userdata('id'),
                'username' => $this->session->userdata('username'),
                'role' => $this->session->userdata('role')
            ];
            $data['logged_in_user'] = $logged_in_user;
            $data['citizen'] = $citizen;
            $this->call->view('citizens/verify', $data);
        }
    }

    public function view($id)
    {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            redirect('/auth/login');
            exit;
        }

        $citizen = $this->CitizensModel->get_citizen_by_id($id);
        
        if (!$citizen) {
            $this->session->set_flashdata('error', 'Citizen not found.');
            redirect('/citizens');
            exit;
        }

        $logged_in_user = [
            'id' => $this->session->userdata('id'),
            'username' => $this->session->userdata('username'),
            'role' => $this->session->userdata('role')
        ];
        $data['logged_in_user'] = $logged_in_user;
        $data['citizen'] = $citizen;
        $data['is_admin_view'] = true;
        
        $this->call->view('citizens/profile', $data);
    }

    public function profile()
    {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $user_id = $this->session->userdata('id');
        $citizen = $this->CitizensModel->get_citizen_by_user_id($user_id);

        if ($this->io->method() === 'post') {
            $national_id = $this->io->post('national_id');
            $verification_action = $this->io->post('verification_action');

            $update_data = [
                'first_name' => $this->io->post('first_name'),
                'last_name' => $this->io->post('last_name'),
                'middle_name' => $this->io->post('middle_name'),
                'email' => $this->io->post('email'),
                'phone' => $this->io->post('phone'),
                'address' => $this->io->post('address'),
                'birth_date' => $this->io->post('birth_date'),
                'gender' => $this->io->post('gender'),
                'national_id' => $national_id
            ];

            // Handle verification actions
            if (!empty($verification_action) && $citizen && !empty($citizen['national_id'])) {
                if ($verification_action === 'verify') {
                    $update_data['verification_status'] = 'verified';
                    $update_data['verified_at'] = date('Y-m-d H:i:s');
                } elseif ($verification_action === 'reject') {
                    $update_data['verification_status'] = 'rejected';
                    // Optionally clear verified_at if rejecting
                    $update_data['verified_at'] = null;
                }
            } elseif (!empty($national_id) && $citizen) {
                // Default behavior for profile update with national_id
                $update_data['verification_status'] = 'verified';
                $update_data['verified_at'] = date('Y-m-d H:i:s');
            }

            if ($citizen) {
                $this->CitizensModel->update($citizen['id'], $update_data);
            } else {
                $update_data['user_id'] = $user_id;
                $update_data['created_at'] = date('Y-m-d H:i:s');
                $this->CitizensModel->insert($update_data);
            }
            redirect('/dashboard');
        } else {
            $logged_in_user = [
                'id' => $this->session->userdata('id'),
                'username' => $this->session->userdata('username'),
                'role' => $this->session->userdata('role')
            ];
            $data['logged_in_user'] = $logged_in_user;
            $data['citizen'] = $citizen;
            $this->call->view('citizens/profile', $data);
        }
    }
}
