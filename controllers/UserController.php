<?php

$projectPath = $_SERVER['DOCUMENT_ROOT'];
require_once "$projectPath/eventSys/models/User.php";

class UserController
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

            if (count($errors) === 0) {
                // Sanitize and prepare input data
                $firstName = self::sanitizeInput($input['first_name']);
                $lastName = self::sanitizeInput($input['last_name']);
                $username = self::sanitizeInput($input['username']);
                $email = self::sanitizeInput($input['email']);
                $password = password_hash(self::sanitizeInput($input['password']), PASSWORD_DEFAULT);
                $mobileNo = self::sanitizeInput($input['mobile']);

                try {
                    $result = User::create($firstName, $lastName, $username, $email, $password, $mobileNo);
                    if ($result) {
                        self::sendJsonResponse('success', 'Registration successful!');
                    } else {
                        self::sendJsonResponse('error', 'Registration failed. Please try again.');
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

            if (empty($input['username']) || empty($input['password'])) {
                self::sendJsonResponse('error', 'Username and password are required.');
            } else {
                $username = self::sanitizeInput($input['username']);
                $password = self::sanitizeInput($input['password']);

                try {
                    $resultset = Database::getInstance()->search('users', ['id', 'username', 'password', 'is_admin', 'first_name', 'last_name', 'email', 'mobile_no'], "username = '$username'");

                    if ($resultset->num_rows == 1) {
                        $row = $resultset->fetch_assoc();
                        if (password_verify($password, $row['password'])) {
                            session_start();
                            $_SESSION['user'] = $row;
                            $_SESSION['user_id'] = $row['id'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['is_admin'] = $row['is_admin'];
                            self::sendJsonResponse('success', 'Login successful!', ['user_id' => $row['id']]);
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

    public static function logout()
    {
        session_start();
        session_destroy();
        header('Location: /eventsys/view/login.php');
        self::sendJsonResponse('success', 'Logged out successfully.');
    }

    public static function viewProfile()
    {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $userData = User::getById($userId);

            if ($userData) {
                self::sendJsonResponse('success', 'Profile fetched successfully.', $userData);
            } else {
                self::sendJsonResponse('error', 'Error: User not found.');
            }
        } else {
            self::sendJsonResponse('error', 'You need to be logged in to view your profile.');
        }
    }

    public static function updateProfile()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
            $input = self::getJsonInput();
            $userId = $_SESSION['user_id'];

            // Sanitize updated profile data
            $firstName = self::sanitizeInput($input['first_name'] ?? '');
            $lastName = self::sanitizeInput($input['last_name'] ?? '');
            $username = self::sanitizeInput($input['username'] ?? '');
            $email = self::sanitizeInput($input['email'] ?? '');
            $mobileNo = self::sanitizeInput($input['mobile'] ?? '');

            $data = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
                'email' => $email,
                'mobile_no' => $mobileNo
            ];

            $result = User::update($userId, $data);

            if ($result) {
                self::sendJsonResponse('success', 'Profile updated successfully!');
            } else {
                self::sendJsonResponse('error', 'Error: Profile update failed. Please try again.');
            }
        } else {
            self::sendJsonResponse('error', 'Invalid request method or user not authenticated.');
        }
    }
}
