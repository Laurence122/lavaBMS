<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Migration_Create_notifications_table extends Migration
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
                'constraint' => 11,
            ],
            'message' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'is_read' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '0',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('notifications');
    }

    public function down()
    {
        $this->dbforge->drop_table('notifications');
    }
}