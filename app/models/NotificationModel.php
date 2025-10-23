<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class NotificationModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->call->library('email');
        $create_table_sql = "
            CREATE TABLE IF NOT EXISTS `notifications` (
                `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT(11) NOT NULL,
                `message` VARCHAR(255) NOT NULL,
                `is_read` TINYINT(1) NOT NULL DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $this->db->raw($create_table_sql);
    }

    public function create_notification($user_id, $message) {
        $data = [
            'user_id' => $user_id,
            'message' => $message
        ];
        return $this->db->table('notifications')->insert($data);
    }

    public function get_unread_notifications($user_id) {
        return $this->db->table('notifications')->where('user_id', $user_id)->where('is_read', 0)->get_all();
    }

    public function mark_as_read($user_id) {
        return $this->db->table('notifications')->where('user_id', $user_id)->update(['is_read' => 1]);
    }

    public function send_email_notification($user_email, $subject, $message) {
        if (!$user_email) {
            return false;
        }

        // For development/demo purposes, we'll log the email instead of sending
        // In production, configure SMTP settings in php.ini or use a service like SendGrid/Mailgun
        $log_message = "Email would be sent to: {$user_email}\nSubject: {$subject}\nMessage: " . strip_tags($message) . "\n";
        error_log($log_message, 3, 'runtime/email_notifications.log');

        // For demo purposes, show a success message
        return true;
    }

    public function send_document_approval_email($user_email, $document_type, $status) {
        $subject = "Document Request " . ucfirst($status);
        $message = $this->get_document_email_template($document_type, $status);
        return $this->send_email_notification($user_email, $subject, $message);
    }

    public function send_permit_approval_email($user_email, $business_name, $status) {
        $subject = "Business Permit Application " . ucfirst($status);
        $message = $this->get_permit_email_template($business_name, $status);
        return $this->send_email_notification($user_email, $subject, $message);
    }

    private function get_document_email_template($document_type, $status) {
        $status_messages = [
            'approved' => [
                'title' => 'Document Approved',
                'message' => "Your request for <strong>" . ucfirst(str_replace('_', ' ', $document_type)) . "</strong> has been approved.",
                'action' => "You can now pick up your document at the barangay hall."
            ],
            'rejected' => [
                'title' => 'Document Rejected',
                'message' => "Your request for <strong>" . ucfirst(str_replace('_', ' ', $document_type)) . "</strong> has been rejected.",
                'action' => "Please contact the barangay office for more information or to submit a new request."
            ]
        ];

        $template = $status_messages[$status] ?? $status_messages['approved'];

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f8f9fa; }
                .footer { text-align: center; padding: 20px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Barangay Management System</h2>
                    <h3>{$template['title']}</h3>
                </div>
                <div class='content'>
                    <p>Dear Citizen,</p>
                    <p>{$template['message']}</p>
                    <p>{$template['action']}</p>
                    <p>If you have any questions, please contact the barangay office.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from the Barangay Management System.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    private function get_permit_email_template($business_name, $status) {
        $status_messages = [
            'approved' => [
                'title' => 'Business Permit Approved',
                'message' => "Your business permit application for <strong>$business_name</strong> has been approved.",
                'action' => "You can now pick up your business permit certificate at the barangay hall."
            ],
            'rejected' => [
                'title' => 'Business Permit Rejected',
                'message' => "Your business permit application for <strong>$business_name</strong> has been rejected.",
                'action' => "Please contact the barangay office for more information or to submit a new application."
            ]
        ];

        $template = $status_messages[$status] ?? $status_messages['approved'];

        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #28a745; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f8f9fa; }
                .footer { text-align: center; padding: 20px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Barangay Management System</h2>
                    <h3>{$template['title']}</h3>
                </div>
                <div class='content'>
                    <p>Dear Business Owner,</p>
                    <p>{$template['message']}</p>
                    <p>{$template['action']}</p>
                    <p>If you have any questions, please contact the barangay office.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from the Barangay Management System.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
