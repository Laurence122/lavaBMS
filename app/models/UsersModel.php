<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {
    protected $table = 'users';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_user_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    public function get_user_by_username($username)
    {
        return $this->db->table($this->table)
                        ->where('username', $username)
                        ->get();
    }

    public function get_user_by_email($email)
    {
        return $this->db->table($this->table)
                        ->where('email', $email)
                        ->get();
    }

    public function update_password($user_id, $new_password) {
        return $this->db->table($this->table)
                        ->where('id', $user_id)
                        ->update([
                            'password' => password_hash($new_password, PASSWORD_DEFAULT)
                        ]);
    }

    public function get_all_users()
    {
        return $this->db->table($this->table)->get_all();
    }

    public function get_users_by_role($role)
    {
        return $this->db->table($this->table)
                        ->where('role', $role)
                        ->get_all();
    }

    public function get_logged_in_user()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user']['id'])) {
            return $this->get_user_by_id($_SESSION['user']['id']);
        }

        return null;
    }

    public function page($q = '', $records_per_page = null, $page = null)
    {
        $this->apply_soft_delete(false); // Apply soft delete by default

        if (is_null($page)) {
            return $this->db->table($this->table)->get_all();
        } else {
            $query = $this->db->table($this->table);

            if (!empty($q)) {
                $query->group_start()
                      ->like('id', '%' . $q . '%')
                      ->or_like('username', '%' . $q . '%')
                      ->or_like('email', '%' . $q . '%')
                      ->or_like('role', '%' . $q . '%')
                      ->group_end();
            }

            $countQuery = clone $query;
            $data['total_rows'] = $countQuery->select_count('*', 'count')->get()['count'];

            $query->pagination($records_per_page, $page);
            $data['records'] = $query->get_all();

            return $data;
        }
    }

    public function soft_delete($id)
    {
        if ($this->has_soft_delete) {
            $data = [$this->soft_delete_column => date('Y-m-d H:i:s')];
            return $this->db->table($this->table)->where($this->primary_key, $id)->update($data);
        }
        return $this->delete($id);
    }

    public function delete($id)
    {
        return $this->db->table($this->table)->where($this->primary_key, $id)->delete();
    }
}
