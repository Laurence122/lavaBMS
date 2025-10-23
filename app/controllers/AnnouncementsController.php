<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AnnouncementsController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
        $this->call->model('AnnouncementsModel');
        $this->call->model('NotificationModel');
        $this->call->model('UserModel');
    }

    public function index() {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
            redirect('/auth/login');
            exit;
        }

        $data['announcements'] = $this->AnnouncementsModel->get_all_announcements();
        $this->call->view('announcements/index', $data);
    }

    public function create() {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
            redirect('/auth/login');
            exit;
        }

        if ($this->io->method() === 'post') {
            $data = [
                'title' => $this->io->post('title'),
                'content' => $this->io->post('content'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->AnnouncementsModel->insert($data)) {
                $users = $this->UserModel->get_staff_and_admins();
                foreach ($users as $user) {
                    $this->NotificationModel->create_notification($user['id'], 'A new announcement has been posted.');
                }
                redirect('/announcements');
            } else {
                $data['error'] = 'Failed to create announcement.';
                $this->call->view('announcements/create', $data);
            }
        } else {
            $this->call->view('announcements/create');
        }
    }

    public function edit($id) {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
            redirect('/auth/login');
            exit;
        }

        if ($this->io->method() === 'post') {
            $data = [
                'title' => $this->io->post('title'),
                'content' => $this->io->post('content'),
            ];

            if ($this->AnnouncementsModel->update($id, $data)) {
                redirect('/announcements');
            } else {
                $data['error'] = 'Failed to update announcement.';
                $data['announcement'] = $this->AnnouncementsModel->get_announcement_by_id($id);
                $this->call->view('announcements/edit', $data);
            }
        } else {
            $data['announcement'] = $this->AnnouncementsModel->get_announcement_by_id($id);
            $this->call->view('announcements/edit', $data);
        }
    }

    public function delete($id) {
        if (!$this->auth->is_logged_in() || !$this->auth->is_admin()) {
            redirect('/auth/login');
            exit;
        }

        $this->AnnouncementsModel->delete($id);
        redirect('/announcements');
    }
}
