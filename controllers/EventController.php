<?php

$projectPath = $_SERVER['DOCUMENT_ROOT'];
require_once "$projectPath/eventSys/models/Event.php";

class EventController
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

    public static function submit()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            self::sendJsonResponse('error', 'You need to be logged in to submit an event.');
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
            $input = self::getJsonInput();
            $errors = [];

            // Validate input data
            if (
                empty($input['eventType']) || empty($input['eventDate']) || empty($input['eventTime']) ||
                empty($input['guests']) || empty($input['package']) || empty($input['name']) ||
                empty($input['email']) || empty($input['phone'])
            ) {
                $errors[] = 'All fields are required.';
            }

            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format.';
            }

            if (count($errors) === 0) {
                // Sanitize and prepare input data
                $eventType = self::sanitizeInput($input['eventType']);
                $eventDate = self::sanitizeInput($input['eventDate']);
                $eventTime = self::sanitizeInput($input['eventTime']);
                $numGuests = self::sanitizeInput($input['guests']);
                $addons = isset($input['addons']) ? array_map('self::sanitizeInput', $input['addons']) : [];
                $contactName = self::sanitizeInput($input['name']);
                $contactEmail = self::sanitizeInput($input['email']);
                $contactPhone = self::sanitizeInput($input['phone']);
                $specialRequests = isset($input['requests']) ? self::sanitizeInput($input['requests']) : '';

                try {
                    // Attempt to create the event
                    $result = Event::create($eventType, $eventDate, $eventTime, $numGuests, $addons, $contactName, $contactEmail, $contactPhone, $specialRequests);

                    if ($result) {
                        self::sendJsonResponse('success', 'Booking submitted successfully! It will be reviewed by an admin.');
                    } else {
                        self::sendJsonResponse('error', 'Booking submission failed. Please try again.');
                    }
                } catch (Exception $e) {
                    self::sendJsonResponse('error', 'An error occurred: ' . $e->getMessage());
                }
            } else {
                self::sendJsonResponse('error', implode('<br>', $errors));
            }
        } else {
            self::sendJsonResponse('error', 'Invalid request method or user not authenticated.');
        }
    }

    public static function listAll()
    {
        try {
            $allEvents = Event::getAllEvents();
            $response = ['status' => 'success', 'events' => $allEvents];

            $projectPath = $_SERVER['DOCUMENT_ROOT'];
            include "$projectPath/eventSys/view/event_list.php";
        } catch (Exception $e) {
            $response = ['status' => 'error', 'message' => 'Error fetching events: ' . $e->getMessage()];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public static function listByUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']["id"];
            try {
                $userEvents = Event::getEventsByUserId($userId);
                $response = ['status' => 'success', 'events' => $userEvents];

                $projectPath = $_SERVER['DOCUMENT_ROOT'];
                include "$projectPath/eventSys/view/my-events.php";
            } catch (Exception $e) {
                $response = ['status' => 'error', 'message' => 'Error fetching your events: ' . $e->getMessage()];
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else {
            $response = ['status' => 'error', 'message' => 'You need to be logged in to view your events.'];
            header('Content-Type: application/json');
            echo json_encode($response);
        }

    }
}
