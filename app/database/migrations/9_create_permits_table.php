<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Migration_Create_permits_table extends Migration
{
    public function up()
    {
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
            'business_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'permit_type' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'pending'
            ],
            'fee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '500.00'
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
        $this->dbforge->create_table('permits');
    }

    public function down()
    {
        $this->dbforge->drop_table('permits');
    }
}
