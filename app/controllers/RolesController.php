<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class RolesController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
        $this->call->model('RolesModel');
    }

    public function index() {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
            redirect('/auth/login');
            exit;
        }

        $data['roles'] = $this->RolesModel->get_all_roles();
        $this->call->view('roles/index', $data);
    }

    public function create() {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
            redirect('/auth/login');
            exit;
        }

        if ($this->io->method() === 'post') {
            $data = [
                'role_name' => $this->io->post('role_name'),
            ];

            if ($this->RolesModel->insert($data)) {
                redirect('/roles');
            } else {
                $data['error'] = 'Failed to create role.';
                $this->call->view('roles/create', $data);
            }
        } else {
            $this->call->view('roles/create');
        }
    }

    public function edit($id) {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
            redirect('/auth/login');
            exit;
        }

        if ($this->io->method() === 'post') {
            $data = [
                'role_name' => $this->io->post('role_name'),
            ];

            if ($this->RolesModel->update($id, $data)) {
                redirect('/roles');
            } else {
                $data['error'] = 'Failed to update role.';
                $data['role'] = $this->RolesModel->get_role_by_id($id);
                $this->call->view('roles/edit', $data);
            }
        } else {
            $data['role'] = $this->RolesModel->get_role_by_id($id);
            $this->call->view('roles/edit', $data);
        }
    }

    public function delete($id) {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
            redirect('/auth/login');
            exit;
        }

        $this->RolesModel->delete($id);
        redirect('/roles');
    }
}
