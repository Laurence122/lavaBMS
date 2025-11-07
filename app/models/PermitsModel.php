<?php

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class PermitsModel extends Model
{
    protected $table = 'permits';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_permit_by_id($id)
    {
        return $this->db->table($this->table)
            ->where('id', $id)
            ->get();
    }

    public function get_permits_by_user($user_id)
    {
        return $this->db->table($this->table)
            ->where('user_id', $user_id)
            ->get_all();
    }

    public function get_pending_permits()
    {
        return $this->db->table($this->table)
            ->where('status', 'pending')
            ->get_all();
    }

    public function get_permits_by_status($status)
    {
        return $this->db->table($this->table)
            ->select('permits.*, CONCAT(citizens.first_name, " ", citizens.last_name) as owner_name, users.username')
            ->join('users', 'users.id = permits.user_id')
            ->left_join('citizens', 'citizens.user_id = permits.user_id')
            ->where('permits.status', $status)
            ->get_all();
    }

    public function get_all_permits()
    {
        return $this->db->table($this->table)
            ->select('permits.*, CONCAT(citizens.first_name, " ", citizens.last_name) as owner_name, users.username')
            ->join('users', 'users.id = permits.user_id')
            ->left_join('citizens', 'citizens.user_id = permits.user_id')
            ->get_all();
    }

    public function insert($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function update($id, $data)
    {
        return $this->db->table($this->table)->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->db->table($this->table)->where('id', $id)->delete();
    }

    public function page($q = '')
    {
        $query = $this->db->table($this->table)
            ->select('permits.*, CONCAT(citizens.first_name, " ", citizens.last_name) as owner_name, users.username')
            ->join('users', 'users.id = permits.user_id')
            ->left_join('citizens', 'citizens.user_id = permits.user_id');

        if (!empty($q)) {
            $query->group_start()
                ->like('permits.permit_type', '%' . $q . '%')
                ->or_like('permits.status', '%' . $q . '%')
                ->or_like('users.username', '%' . $q . '%')
                ->group_end();
        }

        $data['records'] = $query->get_all();
        return $data;
    }
}
