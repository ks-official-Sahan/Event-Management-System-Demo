<?php
require_once 'Database.php';
require_once 'controllers/UserController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/EventController.php';

// Basic routing
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri == '/register' && $requestMethod == 'POST') {
    UserController::register();
} elseif ($requestUri == '/login' && $requestMethod == 'POST') {
    UserController::login();
} elseif ($requestUri == '/admin/register' && $requestMethod == 'POST') {
    AdminController::register();
} elseif ($requestUri == '/admin/login' && $requestMethod == 'POST') {
    AdminController::login();
} elseif ($requestUri == '/submit-event' && $requestMethod == 'POST') {
    EventController::submit();
} elseif ($requestUri == '/admin/review-events') {
    AdminController::reviewEvents();
} elseif ($requestUri == '/profile') {
    UserController::viewProfile();
} else {
    echo "404 Not Found";
}