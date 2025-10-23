<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class RolesModel extends Model {
    protected $table = 'roles';
    protected $primary_key = 'id';

    public function __construct() {
        parent::__construct();
    }

    public function get_all_roles() {
        return $this->db->table($this->table)->get_all();
    }

    public function get_role_by_id($id) {
        return $this->db->table($this->table)->where('id', $id)->get();
    }

    public function insert($data) {
        return $this->db->table($this->table)->insert($data);
    }

    public function update($id, $data) {
        return $this->db->table($this->table)->where('id', $id)->update($data);
    }

    public function delete($id) {
        return $this->db->table($this->table)->where('id', $id)->delete();
    }
}
