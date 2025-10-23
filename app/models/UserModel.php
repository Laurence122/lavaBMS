<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UserModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_staff_and_admins() {
        return $this->db->table('users')->where('role', 'admin')->or_where('role', 'staff')->get_all();
    }

    public function get_user_by_id($id) {
        return $this->db->table('users')->where('id', $id)->get();
    }
}
