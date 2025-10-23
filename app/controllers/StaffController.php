<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StaffController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
        $this->call->model('StaffModel');
        $this->call->model('UsersModel');
        $this->call->library('pagination');
    }

    public function index($page = 1)
    {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
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

        $records_per_page = 10;

        $tasks_data = $this->StaffModel->page($q, $records_per_page, $page);

        $data['tasks'] = $tasks_data['records'];
        $total_rows = $tasks_data['total_rows'];

        $this->pagination->set_options([
            'first_link'     => '',
            'last_link'      => '',
            'next_link'      => 'Next',
            'prev_link'      => 'Previous',
            'page_delimiter' => '/',
            'reuse_query_string' => true
        ]);
        $this->pagination->set_theme('github');
        $this->pagination->initialize($total_rows, $records_per_page, $page, 'staff/index');
        $data['page'] = $this->pagination->paginate();

        $this->call->view('staff/index', $data);
    }

    public function assign_task()
    {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
            redirect('/auth/login');
            exit;
        }

        if ($this->io->method() === 'post') {
            $data = [
                'task_description' => $this->io->post('task_description'),
                'assigned_to' => $this->io->post('assigned_to'),
                'priority' => $this->io->post('priority'),
                'status' => 'pending',
                'assigned_at' => date('Y-m-d H:i:s'),
                'due_date' => $this->io->post('due_date')
            ];

            if ($this->StaffModel->insert($data)) {
                redirect('/staff');
            } else {
                $data['error'] = 'Failed to assign task.';
                $this->call->view('staff/assign_task', $data);
            }
        } else {
            $data['staff_members'] = $this->UsersModel->get_users_by_role('staff');
            $this->call->view('staff/assign_task', $data);
        }
    }

    public function update_progress($id)
    {
        if (!$this->auth->is_logged_in()) {
            redirect('/auth/login');
            exit;
        }

        $task = $this->StaffModel->get_task_by_id($id);
        if (!$task) {
            redirect('/dashboard');
            return;
        }

        // Check if user can update this task
        $can_update = $this->auth->is_admin() || ($this->auth->is_staff() && $task['assigned_to'] == $this->session->userdata('id'));
        if (!$can_update) {
            redirect('/dashboard');
            return;
        }

        if ($this->io->method() === 'post') {
            $update_data = [
                'status' => $this->io->post('status'),
                'progress_notes' => $this->io->post('progress_notes'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->StaffModel->update($id, $update_data)) {
                redirect('/dashboard');
            } else {
                echo 'Failed to update task progress.';
            }
        } else {
            $data['task'] = $task;
            $this->call->view('staff/update_progress', $data);
        }
    }

    public function my_tasks()
    {
        if (!$this->auth->is_logged_in() || !$this->auth->is_staff()) {
            redirect('/dashboard');
            exit;
        }

        $user_id = $this->session->userdata('id');
        $data['tasks'] = $this->StaffModel->get_tasks_by_staff($user_id);
        $data['logged_in_user'] = [
            'id' => $this->session->userdata('id'),
            'username' => $this->session->userdata('username'),
            'role' => $this->session->userdata('role')
        ];

        $this->call->view('staff/my_tasks', $data);
    }
}
