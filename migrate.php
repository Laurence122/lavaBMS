<?php
define('PREVENT_DIRECT_ACCESS', TRUE);
require_once 'index.php';

$controller = new MigrationController();
$controller->run();
echo "Migrations completed.\n";
?>
