<?php
require_once 'Database.php';
require_once 'controllers/UserController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/EventController.php';

// Basic routing
$requestUri = str_replace('/eventsys', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Routing logic
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
} elseif ($requestUri == '/admin/events' && $requestMethod == 'GET') {
    EventController::listAll();
} elseif ($requestUri == '/view/my-events' && $requestMethod == 'GET') {
    EventController::listByUser();
} elseif ($requestUri == '/logout') {
    UserController::logout();
} elseif ($requestUri == '/profile' && $requestMethod == 'GET') {
    UserController::viewProfile();
} elseif ($requestUri == '/profile/update' && $requestMethod == 'POST') {
    UserController::updateProfile();
} elseif ($requestUri == '/admin/users' && $requestMethod == 'GET') {
    AdminController::listUsers();
} elseif (preg_match('/^\/admin\/users\/(\d+)\/profile$/', $requestUri, $matches) && $requestMethod == 'GET') {
    $userId = $matches[1];
    AdminController::viewUserProfile($userId);
} elseif (preg_match('/^\/admin\/users\/(\d+)\/profile\/update$/', $requestUri, $matches) && $requestMethod == 'POST') {
    $userId = $matches[1];
    AdminController::updateUserProfile($userId);
} elseif ($requestUri == '/admin/profile' && $requestMethod == 'GET') {
    AdminController::viewAdminProfile();
} elseif ($requestUri == '/admin/profile/update' && $requestMethod == 'POST') {
    AdminController::updateAdminProfile();
} else {
    // Handling 404 errors
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => '404 Not Found']);
    header('Location: /eventsys/view/login.php');
}
