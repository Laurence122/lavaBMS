<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class NotificationsModel extends Model {
    protected $table = 'notifications';

    public function get_unread_notifications($user_id) {
        return $this->db->table($this->table)
            ->where('user_id', $user_id)
            ->where('is_read', 0)
            ->get_all();
    }

    public function mark_as_read($user_id) {
        return $this->db->table($this->table)
            ->where('user_id', $user_id)
            ->update(['is_read' => 1]);
    }

    public function add_notification($user_id, $message) {
        return $this->db->table($this->table)->insert([
            'user_id' => $user_id,
            'message' => $message,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
