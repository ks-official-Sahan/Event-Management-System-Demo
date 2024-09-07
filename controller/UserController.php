<?php
require_once '../models/User.php';

class UserController
{
    public static function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstName = sanitizeInput($_POST['first_name']);
            $lastName = sanitizeInput($_POST['last_name']);
            $username = sanitizeInput($_POST['username']);
            $email = sanitizeInput($_POST['email']);
            $password = password_hash(sanitizeInput($_POST['password']), PASSWORD_DEFAULT);
            $dob = sanitizeInput($_POST['dob']);
            $line1 = sanitizeInput($_POST['line1']);
            $line2 = sanitizeInput($_POST['line2']);
            $line3 = sanitizeInput($_POST['line3']);
            $zipcode = sanitizeInput($_POST['zip']);
            $province = sanitizeInput($_POST['province']);
            $mobileNo = sanitizeInput($_POST['mobile']);

            $result = User::create($firstName, $lastName, $username, $email, $password, $dob, $line1, $line2, $line3, $zipcode, $province, $mobileNo);

            if ($result) {
                $message = "Registration successful!";
            } else {
                $message = "Error: Registration failed. Please try again.";
            }
        }

        include '../views/register.php';
    }

    public static function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = sanitizeInput($_POST['username']);
            $password = sanitizeInput($_POST['password']);

            $resultset = Database::getInstance()->search('users', ['id', 'username', 'password', 'is_admin'], "username = '$username'");

            if ($resultset->num_rows == 1) {
                $row = $resultset->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['is_admin'] = $row['is_admin'];
                    $message = "Login successful!";
                } else {
                    $message = "Invalid password.";
                }
            } else {
                $message = "Invalid username.";
            }
        }

        include '../views/login.php';
    }

    public static function viewProfile()
    {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $userData = User::getById($userId);

            if ($userData) {
                include '../views/profile.php';
            } else {
                echo "Error: User not found.";
            }
        } else {
            echo "You need to be logged in to view your profile.";
        }
    }

    public static function logout()
    {
        session_start(); // Start the session if it hasn't already been started
        session_destroy(); // Destroy the session
        header('Location: /login.php'); // Redirect to the login page
        exit();
    }
}