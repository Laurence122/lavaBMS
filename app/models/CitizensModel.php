<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CitizensModel extends Model {
    protected $table = 'citizens';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_citizen_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    public function get_citizen_by_user_id($user_id)
    {
        return $this->db->table($this->table)
                        ->where('user_id', $user_id)
                        ->get();
    }

    public function get_all_citizens()
    {
        return $this->db->table($this->table)->get_all();
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
        $query = $this->db->table($this->table);

        if (!empty($q)) {
            $query->group_start()
                    ->like('first_name', '%' . $q . '%')
                    ->or_like('last_name', '%' . $q . '%')
                    ->or_like('email', '%' . $q . '%')
                    ->group_end();
        }

        $data['records'] = $query->get_all();

        return $data;
    }

    public function verify_national_id($user_id, $national_id)
    {
        // Check if the national_id matches the one stored for this user
        $citizen = $this->get_citizen_by_user_id($user_id);

        if ($citizen && isset($citizen['national_id']) && $citizen['national_id'] === $national_id) {
            return true;
        }

        return false;
    }

    public function update_verification_status($user_id, $status, $national_id = null)
    {
        $data = [
            'verification_status' => $status,
            'verified_at' => date('Y-m-d H:i:s')
        ];

        if ($national_id) {
            $data['national_id'] = $national_id;
        }

        return $this->db->table($this->table)
                        ->where('user_id', $user_id)
                        ->update($data);
    }

    public function is_verified($user_id)
    {
        $citizen = $this->get_citizen_by_user_id($user_id);
        return $citizen && isset($citizen['verification_status']) && $citizen['verification_status'] === 'verified';
    }

    public function get_total_citizens_count()
    {
        $result = $this->db->table($this->table)
                           ->select('COUNT(*) as total')
                           ->get();
        return $result ? (int)$result['total'] : 0;
    }

    public function get_pending_verifications_count()
    {
        $result = $this->db->table($this->table)
                           ->select('COUNT(*) as total')
                           ->where('national_id IS NOT NULL', null, false)
                           ->where('national_id !=', '')
                           ->where_in('verification_status', ['pending', null])
                           ->get();
        return $result ? (int)$result['total'] : 0;
    }
}
