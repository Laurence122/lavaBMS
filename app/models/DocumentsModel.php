<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DocumentsModel extends Model {
    protected $table = 'documents';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_document_by_id($id)
    {
        return $this->db->table($this->table)
                        ->select('documents.*, users.username')
                        ->join('users', 'users.id = documents.user_id')
                        ->where('documents.id', $id)
                        ->get();
    }

    public function get_documents_by_user($user_id)
    {
        return $this->db->table($this->table)
                        ->select('documents.*, users.username')
                        ->join('users', 'users.id = documents.user_id')
                        ->where('documents.user_id', $user_id)
                        ->get_all();
    }

    public function get_pending_documents()
    {
        return $this->db->table($this->table)
                        ->select('documents.*, users.username')
                        ->join('users', 'users.id = documents.user_id')
                        ->where('status', 'pending')
                        ->get_all();
    }

    public function get_all_documents()
    {
        return $this->db->table($this->table)
                        ->select('documents.*, users.username')
                        ->join('users', 'users.id = documents.user_id')
                        ->get_all();
    }
    
    public function get_document_type_distribution()
    {
        return $this->db->table($this->table)
                        ->select('document_type, COUNT(*) as count')
                        ->group_by('document_type')
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


}
