<?php
require_once '../models/User.php';
require_once '../models/Event.php';

class AdminController
{
    public static function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = sanitizeInput($_POST['title']);
            $firstName = sanitizeInput($_POST['first_name']);
            $lastName = sanitizeInput($_POST['last_name']);
            $nwi = sanitizeInput($_POST['nwi']);
            $email = sanitizeInput($_POST['email']);
            $password = password_hash(sanitizeInput($_POST['password']), PASSWORD_DEFAULT);
            $mobileNo = sanitizeInput($_POST['mobile']);
            $position = sanitizeInput($_POST['position']);

            $result = Admin::create($title, $firstName, $lastName, $nwi, $email, $password, $mobileNo, $position);

            if ($result) {
                $message = "Admin registration successful!";
            } else {
                $message = "Error: Admin registration failed. Please try again.";
            }
        }

        include '../views/admin_register.php';
    }

    public static function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = sanitizeInput($_POST['username']);
            $password = sanitizeInput($_POST['password']);

            $resultset = Database::getInstance()->search('users', ['id', 'username', 'password', 'is_admin'], "username = '$username'");

            if ($resultset->num_rows == 1) {
                $row = $resultset->fetch_assoc();
                if (password_verify($password, $row['password']) && $row['is_admin']) {
                    session_start();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['is_admin'] = $row['is_admin'];
                    $message = "Admin login successful!";
                } else {
                    $message = "Invalid credentials or not an admin.";
                }
            } else {
                $message = "Invalid username.";
            }
        }

        include '../views/admin_login.php';
    }

    public static function reviewEvents()
    {
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            $pendingEvents = Event::getPendingEvents();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['approve_event'])) {
                    $eventId = sanitizeInput($_POST['event_id']);
                    Event::approveEvent($eventId);
                    $message = "Event approved!";
                } elseif (isset($_POST['reject_event'])) {
                    $eventId = sanitizeInput($_POST['event_id']);
                    Event::rejectEvent($eventId);
                    $message = "Event rejected!";
                }
            }

            include '../views/review_events.php';
        } else {
            echo "You are not authorized to view this page.";
        }
    }
}