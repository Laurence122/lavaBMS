<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Migration_Add_missing_columns_to_permits_table extends Migration {

    public function up() {
        $this->dbforge->add_column('permits', [
            'business_address' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'after' => 'business_name'
            ],
            'owner_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
                'after' => 'business_address'
            ],
            'applied_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'after' => 'fee'
            ]
        ]);
    }

    public function down() {
        $this->dbforge->drop_column('permits', 'business_address');
        $this->dbforge->drop_column('permits', 'owner_name');
        $this->dbforge->drop_column('permits', 'applied_at');
    }
}
