<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Migration_Add_document_id_to_payments_table extends Migration {

    public function up() {
        $this->db->query("ALTER TABLE payments ADD COLUMN document_id INT(11) UNSIGNED NULL AFTER permit_id");
        $this->db->query("ALTER TABLE payments ADD KEY document_id (document_id)");
    }

    public function down() {
        $this->dbforge->drop_column('payments', 'document_id');
    }
}
