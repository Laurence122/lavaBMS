<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Migration_Add_pickup_time_to_documents_table extends Migration {

    public function up() {
        $this->dbforge->add_column('documents', [
            'pickup_time' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'after' => 'status'
            ]
        ]);
    }

    public function down() {
        $this->dbforge->drop_column('documents', 'pickup_time');
    }
}
