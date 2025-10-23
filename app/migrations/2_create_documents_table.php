<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Create_documents_table {
    public function up() {
        $this->call->database_forge();
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'document_type' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'purpose' => [
                'type' => 'TEXT'
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'pending'
            ],
            'requested_at' => [
                'type' => 'DATETIME'
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP'
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('documents');
    }

    public function down() {
        $this->call->database_forge();
        $this->dbforge->drop_table('documents');
    }
}
