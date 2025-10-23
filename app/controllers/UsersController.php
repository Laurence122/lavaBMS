<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
        $this->call->model('UsersModel');
    }



    public function register()
    {
        $error = '';

        if ($this->io->method() == 'post') {
            $username = $this->io->post('username');
            $email = $this->io->post('email');
            $password = $this->io->post('password');
            $role = $this->io->post('role') ?: 'citizen'; // Default to citizen for public registration

            if ($this->auth->register($username, $email, $password, $role)) {
                redirect('/dashboard');
            } else {
                $error = "Registration failed. Username or email may already exist.";
            }
        }

        $this->call->view('auth/register', ['error' => $error]);
    }

    public function login()
    {
        $error = '';

        if ($this->io->method() == 'post') {
            $username = $this->io->post('username');
            $password = $this->io->post('password');

            if ($this->auth->login($username, $password)) {
                $user_role = $this->session->userdata('role');
                if ($user_role === 'citizen') {
                    $user_id = $this->session->userdata('id');
                    $this->call->model('CitizensModel');
                    $citizen = $this->CitizensModel->get_citizen_by_user_id($user_id);
                    if (empty($citizen) || empty($citizen['first_name']) || empty($citizen['last_name'])) {
                        redirect('/citizens/profile');
                    } else {
                        redirect('/dashboard');
                    }
                } else {
                    redirect('/dashboard');
                }
            } else {
                $error = "Invalid username or password!";
            }
        }

        $this->call->view('auth/login', ['error' => $error]);
    }

    public function logout()
    {
        $this->auth->logout();
        redirect('/auth/login');
    }
}
