<?php

$projectPath = $_SERVER['DOCUMENT_ROOT'];
require_once "$projectPath/eventSys/models/Admin.php";
require_once "$projectPath/eventSys/models/Event.php";
require_once "$projectPath/eventSys/models/User.php";

class AdminController
{
    private static function getJsonInput()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    private static function sendJsonResponse($status, $message, $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
        exit;
    }

    private static function sanitizeInput($data)
    {
        $conn = Database::getInstance()->getConnection();
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $conn->real_escape_string($data);
    }

    public static function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $input = self::getJsonInput();
            $errors = [];

            // Input validation 
            if (
                empty($input['first_name']) || empty($input['last_name']) || empty($input['username']) ||
                empty($input['email']) || empty($input['password']) || empty($input['mobile'])
            ) {
                $errors[] = 'All fields are required.';
            }

            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format.';
            }

            if (strlen($input['password']) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
            }

            if ($input['password'] !== $input['cPassword']) {
                $errors[] = 'Passwords do not match.';
            }

            if (count($errors) == 0) {
                $firstName = self::sanitizeInput($input['first_name']);
                $lastName = self::sanitizeInput($input['last_name']);
                $username = self::sanitizeInput($input['username']);
                $email = self::sanitizeInput($input['email']);
                $password = password_hash(self::sanitizeInput($input['password']), PASSWORD_DEFAULT);
                $mobileNo = self::sanitizeInput($input['mobile']);

                try {
                    $result = Admin::create($firstName, $lastName, $email, $password, $mobileNo, $username);

                    if ($result) {
                        self::sendJsonResponse('success', 'Admin registration successful!');
                    } else {
                        self::sendJsonResponse('error', 'Admin registration failed. Please try again.');
                    }
                } catch (Exception $e) {
                    self::sendJsonResponse('error', 'An error occurred: ' . $e->getMessage());
                }
            } else {
                self::sendJsonResponse('error', implode('<br>', $errors));
            }
        } else {
            self::sendJsonResponse('error', 'Invalid request method.');
        }
    }

    public static function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $input = self::getJsonInput();

            // Input validation
            if (empty($input['username']) || empty($input['password'])) {
                self::sendJsonResponse('error', 'Username and password are required.');
            } else {
                $username = self::sanitizeInput($input['username']);
                $password = self::sanitizeInput($input['password']);

                try {
                    $resultset = Database::getInstance()->search('admins', ['id', 'username', 'password'], "username = '$username'");

                    if ($resultset->num_rows == 1) {
                        $row = $resultset->fetch_assoc();
                        if (password_verify($password, $row['password'])) {
                            session_start();
                            $_SESSION['user_id'] = $row['id'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['is_admin'] = true;
                            self::sendJsonResponse('success', 'Admin login successful!');
                        } else {
                            self::sendJsonResponse('error', 'Invalid password.');
                        }
                    } else {
                        self::sendJsonResponse('error', 'Invalid username.');
                    }
                } catch (Exception $e) {
                    self::sendJsonResponse('error', 'An error occurred: ' . $e->getMessage());
                }
            }
        } else {
            self::sendJsonResponse('error', 'Invalid request method.');
        }
    }

    public static function reviewEvents()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            try {
                $pendingEvents = Event::getPendingEvents();
            } catch (Exception $e) {
                $message = "Error fetching pending events: " . $e->getMessage();
                $pendingEvents = [];
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['approve_event'])) {
                    $eventId = self::sanitizeInput($_POST['event_id']);
                    try {
                        if (Event::approveEvent($eventId)) {
                            $message = "Event approved!";
                        } else {
                            $message = "Error approving event. Please try again.";
                        }
                    } catch (Exception $e) {
                        $message = "Error approving event: " . $e->getMessage();
                    }
                } elseif (isset($_POST['reject_event'])) {
                    $eventId = self::sanitizeInput($_POST['event_id']);
                    try {
                        if (Event::rejectEvent($eventId)) {
                            $message = "Event rejected!";
                        } else {
                            $message = "Error rejecting event. Please try again.";
                        }
                    } catch (Exception $e) {
                        $message = "Error rejecting event: " . $e->getMessage();
                    }
                }

                // Re-fetch pending events after approval/rejection
                try {
                    $pendingEvents = Event::getPendingEvents();
                } catch (Exception $e) {
                    $message = "Error fetching pending events: " . $e->getMessage();
                }
            }

            $projectPath = $_SERVER['DOCUMENT_ROOT'];
            include "$projectPath/eventSys/view/review_events.php";
        } else {
            echo "You are not authorized to view this page.";
        }
    }

    public static function listUsers()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            try {
                $users = User::getAll();
            } catch (Exception $e) {
                // Handle the error, set $users to an empty array
                $users = [];
                $message = "Error fetching users: " . $e->getMessage(); // You can display this error message in the view
            }

            $projectPath = $_SERVER['DOCUMENT_ROOT'];
            include "$projectPath/eventSys/view/user_list.php";
        } else {
            echo "You are not authorized to view this page.";
        }
    }

    public static function viewUserProfile($userId)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            self::sendJsonResponse('error', 'You are not authorized to view this page.');
        }

        try {
            $userData = User::getById($userId);
            if ($userData) {
                $projectPath = $_SERVER['DOCUMENT_ROOT'];
                include "$projectPath/eventSys/view/user_profile.php";
                // self::sendJsonResponse('success', 'User profile fetched successfully.', ['user' => $userData]);
            } else {
                self::sendJsonResponse('error', 'User not found.');
            }
        } catch (Exception $e) {
            self::sendJsonResponse('error', 'Error fetching user profile: ' . $e->getMessage());
        }
    }

    public static function updateUserProfile($userId)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            $input = self::getJsonInput();

            // Input validation
            if (
                empty($input['first_name']) || empty($input['last_name']) || empty($input['username']) ||
                empty($input['email']) || empty($input['mobile'])
            ) {
                self::sendJsonResponse('error', 'All fields are required.');
            } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                self::sendJsonResponse('error', 'Invalid email format.');
            } else {
                // Sanitize and update profile data
                $data = [
                    'first_name' => self::sanitizeInput($input['first_name']),
                    'last_name' => self::sanitizeInput($input['last_name']),
                    'username' => self::sanitizeInput($input['username']),
                    'email' => self::sanitizeInput($input['email']),
                    'mobile' => self::sanitizeInput($input['mobile']),
                ];

                try {
                    $result = User::update($userId, $data);
                    if ($result) {
                        self::sendJsonResponse('success', 'User profile updated successfully!');
                    } else {
                        self::sendJsonResponse('error', 'User profile update failed. Please try again.');
                    }
                } catch (Exception $e) {
                    self::sendJsonResponse('error', 'Error updating user profile: ' . $e->getMessage());
                }
            }
        } else {
            self::sendJsonResponse('error', 'Invalid request method or unauthorized access.');
        }
    }

    public static function viewAdminProfile()
    {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            self::sendJsonResponse('error', 'You are not authorized to view this page.');
        }

        try {
            $adminData = Admin::getById($_SESSION['user_id']);
            self::sendJsonResponse('success', 'Admin profile fetched successfully.', ['admin' => $adminData]);
        } catch (Exception $e) {
            self::sendJsonResponse('error', 'Error fetching admin profile: ' . $e->getMessage());
        }
    }

    public static function updateAdminProfile()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            $input = self::getJsonInput();

            // Input validation
            if (
                empty($input['first_name']) || empty($input['last_name']) || empty($input['username']) ||
                empty($input['email']) || empty($input['mobile'])
            ) {
                self::sendJsonResponse('error', 'All fields are required.');
            } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                self::sendJsonResponse('error', 'Invalid email format.');
            } else {
                // Sanitize and update admin profile data
                $data = [
                    'first_name' => self::sanitizeInput($input['first_name']),
                    'last_name' => self::sanitizeInput($input['last_name']),
                    'username' => self::sanitizeInput($input['username']),
                    'email' => self::sanitizeInput($input['email']),
                    'mobile' => self::sanitizeInput($input['mobile']),
                ];

                try {
                    $result = Admin::update($_SESSION['user_id'], $data);
                    if ($result) {
                        self::sendJsonResponse('success', 'Admin profile updated successfully!');
                    } else {
                        self::sendJsonResponse('error', 'Admin profile update failed. Please try again.');
                    }
                } catch (Exception $e) {
                    self::sendJsonResponse('error', 'Error updating admin profile: ' . $e->getMessage());
                }
            }
        } else {
            self::sendJsonResponse('error', 'Invalid request method or unauthorized access.');
        }
    }
}
