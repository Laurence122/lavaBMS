<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Migration_Add_payment_type_to_payments_table extends Migration {

    public function up() {
        $this->db->query("ALTER TABLE payments ADD COLUMN payment_type ENUM('online', 'cash_on_pickup') DEFAULT 'online' AFTER payment_method");
    }

    public function down() {
        $this->dbforge->drop_column('payments', 'payment_type');
    }
}
