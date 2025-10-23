<?php
/**
 * Public index.php - Entry point for web requests
 *
 * This file handles all incoming requests and bootstraps the LavaLust framework.
 */

// Increase memory limit for development
ini_set('memory_limit', '1024M');

// Change directory to the project root and load the main front controller
chdir(dirname(__DIR__));
require 'index.php';
