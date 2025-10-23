<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Migration_Update_approved_documents_to_pending_payment extends Migration
{
    public function up()
    {
        // Update all documents with status 'approved' to 'approved_pending_payment'
        // This assumes that 'approved' documents haven't been paid yet
        $this->db->where('status', 'approved');
        $this->db->update('documents', ['status' => 'approved_pending_payment']);
    }

    public function down()
    {
        // Revert back to 'approved' status
        $this->db->where('status', 'approved_pending_payment');
        $this->db->update('documents', ['status' => 'approved']);
    }
}
