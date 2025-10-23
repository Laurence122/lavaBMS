<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class PaymentModel extends Model {
    protected $table = 'payments';
    protected $primary_key = 'id';

    public function __construct() {
        parent::__construct();
    }

    public function get_by_id($id) {
        return $this->db->table($this->table)->where('id', $id)->get();
    }

    public function get_by_paypal_order_id($paypal_order_id) {
        return $this->db->table($this->table)->where('paypal_order_id', $paypal_order_id)->get();
    }

    public function get_by_user_id($user_id) {
        return $this->db->table($this->table)->where('user_id', $user_id)->get_all();
    }

    public function get_by_permit_id($permit_id) {
        return $this->db->table($this->table)->where('permit_id', $permit_id)->get();
    }

    public function get_by_document_id($document_id) {
        return $this->db->table($this->table)->where('document_id', $document_id)->get();
    }

    public function insert($data) {
        return $this->db->table($this->table)->insert($data);
    }

    public function update($id, $data) {
        return $this->db->table($this->table)->where('id', $id)->update($data);
    }

    public function update_by_paypal_order_id($paypal_order_id, $data) {
        return $this->db->table($this->table)->where('paypal_order_id', $paypal_order_id)->update($data);
    }

    public function get_completed_payments() {
        return $this->db->table($this->table)->where('status', 'completed')->get_all();
    }

    public function get_payment_summary() {
        return $this->db->table($this->table)
            ->select('status, COUNT(*) as count, SUM(amount) as total')
            ->group_by('status')
            ->get_all();
    }
}
