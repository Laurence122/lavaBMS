<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AnnouncementsModel extends Model {
    protected $table = 'announcements';
    protected $primary_key = 'id';

    public function __construct() {
        parent::__construct();
    }

    public function get_all_announcements() {
        return $this->db->table($this->table)->order_by('created_at', 'desc')->get_all();
    }

    public function get_announcement_by_id($id) {
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
