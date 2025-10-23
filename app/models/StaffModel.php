<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StaffModel extends Model {
    protected $table = 'staff_tasks';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_task_by_id($id)
    {
        return $this->db->table($this->table)
                        ->where('id', $id)
                        ->get();
    }

    public function get_tasks_by_staff($staff_id)
    {
        return $this->db->table($this->table)
                        ->where('assigned_to', $staff_id)
                        ->get_all();
    }

    public function get_all_tasks_with_staff_names()
    {
        return $this->db->table($this->table)
                        ->select('staff_tasks.*, users.username as staff_name')
                        ->join('users', 'staff_tasks.assigned_to = users.id')
                        ->get_all();
    }

    public function get_all_tasks()
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

    public function page($q = '', $records_per_page = null, $page = null)
    {
        if (is_null($page)) {
            return $this->db->table($this->table)->get_all();
        } else {
            $query = $this->db->table($this->table);

            if (!empty($q)) {
                $query->group_start()
                      ->like('task_description', '%' . $q . '%')
                      ->or_like('status', '%' . $q . '%')
                      ->group_end();
            }

            $countQuery = clone $query;
            $data['total_rows'] = $countQuery->select_count('*', 'count')->get()['count'];

            $query->pagination($records_per_page, $page);
            $data['records'] = $query->get_all();

            return $data;
        }
    }
}
