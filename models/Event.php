<?php
$projectPath = $_SERVER['DOCUMENT_ROOT'];

require_once "$projectPath/eventSys/Database.php";

class Event
{
    public static function create($eventType, $eventDate, $eventTime, $numGuests, $addons, $contactName, $contactEmail, $contactPhone, $specialRequests)
    {
        $data = [
            'title' => 'Event',
            'event_type' => $eventType,
            'event_date' => $eventDate,
            'event_time' => $eventTime,
            'num_guests' => $numGuests,
            'addons' => implode(",", $addons),
            'contact_name' => $contactName,
            'contact_email' => $contactEmail,
            'contact_phone' => $contactPhone,
            'special_requests' => $specialRequests,
            'status' => 'pending'
        ];

        try {
            return Database::getInstance()->insert('events', $data);
        } catch (Exception $e) {
            // Log the error or handle it appropriately
            echo json_encode(['status' => 'Error', 'message' => $e->getMessage(), 'data' => $data]);
            error_log("Error creating event: " . $e->getMessage());
            return false; // Indicate failure
        }
    }

    public static function getPendingEvents()
    {
        try {
            $result = Database::getInstance()->search('events', ['*'], "status = 'pending'");
            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;

        } catch (Exception $e) {
            // Handle the database error
            error_log("Error fetching pending events: " . $e->getMessage());
            return []; // Return an empty array in case of error
        }
    }

    public static function approveEvent($eventId)
    {
        try {
            return Database::getInstance()->update('events', ['status' => 'approved'], "id = $eventId");
        } catch (Exception $e) {
            // Handle the database error
            error_log("Error approving event: " . $e->getMessage());
            return false;
        }
    }

    public static function rejectEvent($eventId)
    {
        try {
            return Database::getInstance()->update('events', ['status' => 'rejected'], "id = $eventId");
        } catch (Exception $e) {
            // Handle the database error
            error_log("Error rejecting event: " . $e->getMessage());
            return false;
        }
    }

    public static function getAllEvents()
    {
        try {
            $result = Database::getInstance()->search('events', ['*']);
            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        } catch (Exception $e) {
            // Handle the database error
            error_log("Error fetching all events: " . $e->getMessage());
            return []; // Return an empty array in case of error
        }
    }

    public static function getEventsByUserId($userId)
    {
        try {
            $result = Database::getInstance()->search('events', ['*'], "user_id = $userId");
            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        } catch (Exception $e) {
            error_log("Error fetching events by user ID: " . $e->getMessage());
            return [];
        }
    }
}