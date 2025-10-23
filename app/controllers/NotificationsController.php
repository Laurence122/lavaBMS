<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class NotificationsController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('auth', ['ci' => $this]);
    }

    public function get_notifications() {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            return;
        }

        $this->call->model('NotificationsModel');
        $notifications = $this->NotificationsModel->get_unread_notifications($this->session->userdata('id'));

        $this->call->view('notifications/popup', ['notifications' => $notifications]);
    }

    public function mark_as_read() {
        if (!$this->auth->is_logged_in() || (!$this->auth->is_admin() && !$this->auth->is_staff())) {
            return;
        }

        $this->call->model('NotificationsModel');
        $this->NotificationsModel->mark_as_read($this->session->userdata('id'));
    }
}
