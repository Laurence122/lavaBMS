<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Add_fee_to_documents_table {
    public function up() {
        $this->call->database();
        $this->db->query("ALTER TABLE documents ADD COLUMN fee DECIMAL(10,2) DEFAULT 50.00 AFTER purpose");
    }

    public function down() {
        $this->call->database();
        $this->db->query("ALTER TABLE documents DROP COLUMN fee");
    }
}
