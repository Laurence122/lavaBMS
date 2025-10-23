<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Migration_Add_national_id_to_citizens extends Migration {

    public function up() {
        $this->dbforge->add_column('citizens', [
            'national_id' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
                'after' => 'email'
            ],
            'verification_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'verified', 'rejected'],
                'default' => 'pending',
                'after' => 'national_id'
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'after' => 'verification_status'
            ]
        ]);
    }

    public function down() {
        $this->dbforge->drop_column('citizens', 'national_id');
        $this->dbforge->drop_column('citizens', 'verification_status');
        $this->dbforge->drop_column('citizens', 'verified_at');
    }
}
