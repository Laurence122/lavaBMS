<?php

// If the requested URI is a file, let the web server handle it.
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
    return false;
}

// Otherwise, forward the request to the front controller.
require_once 'index.php';
