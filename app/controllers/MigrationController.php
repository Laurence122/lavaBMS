<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class MigrationController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->library('migration');
    }

    public function run() {
        if ($this->migration->current()) {
            echo 'Migrations run successfully.';
        } else {
            show_error($this->migration->error_string());
        }
    }
}
