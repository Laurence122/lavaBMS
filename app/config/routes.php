<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/

// Default route - redirect to login
$router->get('/', function() {
    redirect('/auth/login');
});

$router->get('/migrate', 'MigrationController::run');

// Auth routes
$router->match('/auth/register', 'UsersController::register', ['GET','POST']);
$router->match('/auth/login', 'UsersController::login', ['GET','POST']);
$router->get('/auth/logout', 'UsersController::logout');

// Dashboard
$router->get('/dashboard', 'DashboardController::index');

// Announcements
$router->get('/announcements', 'AnnouncementsController::index');
$router->match('/announcements/create', 'AnnouncementsController::create', ['GET', 'POST']);
$router->match('/announcements/edit/{id}', 'AnnouncementsController::edit', ['GET', 'POST']);
$router->get('/announcements/delete/{id}', 'AnnouncementsController::delete');

// Citizens Management
$router->get('/citizens', 'CitizensController::index');
$router->match('/citizens/create', 'CitizensController::create', ['GET', 'POST']);
$router->match('/citizens/update/{id}', 'CitizensController::update', ['GET', 'POST']);
$router->get('/citizens/delete/{id}', 'CitizensController::delete');
$router->get('/citizens/view/{id}', 'CitizensController::view');
$router->match('/citizens/profile', 'CitizensController::profile', ['GET', 'POST']);
$router->get('/citizens/verifications', 'CitizensController::verifications');
$router->match('/citizens/verify/{id}', 'CitizensController::verify', ['GET', 'POST']);

// Documents Management
$router->get('/documents', 'DocumentsController::index');
$router->match('/documents/request', 'DocumentsController::request', ['GET', 'POST']);
$router->match('/documents/update_status/{id}', 'DocumentsController::update_status', ['GET', 'POST']);
$router->get('/documents/pay/{id}', function($id) {
    redirect('/documents/payment/' . $id);
});
$router->get('/documents/payment/{id}', 'DocumentsController::payment');
$router->get('/documents/download/{id}', 'DocumentsController::download');
$router->post('/documents/set_fee', 'DocumentsController::set_fee');
$router->post('/documents/set_pickup_time', 'DocumentsController::set_pickup_time');
$router->get('/documents/generate_certificate/{id}', 'DocumentsController::generate_certificate');
$router->get('/documents/cancel/{id}', 'DocumentsController::cancel');

// Permits Management
$router->get('/permits', 'PermitsController::index');
$router->match('/permits/apply', 'PermitsController::apply', ['GET', 'POST']);
$router->get('/permits/pay/{id}', function($id) {
    redirect('/permits/payment/' . $id);
});
$router->get('/permits/payment/{id}', 'PermitsController::payment');
$router->match('/permits/renew/{id}', 'PermitsController::renew', ['GET', 'POST']);
$router->match('/permits/update_status/{id}', 'PermitsController::update_status', ['GET', 'POST']);
$router->get('/permits/generate_certificate/{id}', 'PermitsController::generate_certificate');

// Payment Management
$router->post('/payment/create', 'PaymentController::create');
$router->post('/payment/cash', 'PaymentController::create');
$router->get('/payment/approve', 'PaymentController::approve');
$router->get('/payment/cancel', 'PaymentController::cancel');
$router->get('/payment/receipt/{id}', 'PaymentController::generate_receipt');

// Staff Management
$router->get('/staff', 'StaffController::index');
$router->match('/staff/assign_task', 'StaffController::assign_task', ['GET', 'POST']);
$router->match('/staff/update_progress/{id}', 'StaffController::update_progress', ['GET', 'POST']);
$router->get('/staff/my_tasks', 'StaffController::my_tasks');

// User Roles Management
$router->get('/roles', 'RolesController::index');
$router->match('/roles/create', 'RolesController::create', ['GET', 'POST']);
$router->match('/roles/edit/{id}', 'RolesController::edit', ['GET', 'POST']);
$router->get('/roles/delete/{id}', 'RolesController::delete');

// Fallback route for any unmatched requests
$router->match('{any}', function() {
    redirect('/auth/login');
}, ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS']);