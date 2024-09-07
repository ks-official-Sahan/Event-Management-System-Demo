<?php
require_once '../models/Event.php';

class EventController
{
    public static function submit()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
            $categoryId = sanitizeInput($_POST['category_id']);
            $title = sanitizeInput($_POST['title']);
            $description = sanitizeInput($_POST['description']);
            $startDate = sanitizeInput($_POST['start_date']);
            $endDate = sanitizeInput($_POST['end_date']);
            $location = sanitizeInput($_POST['location']);

            $result = Event::create($categoryId, $title, $description, $startDate, $endDate, $location);

            if ($result) {
                $message = "Event submitted successfully! It will be reviewed by an admin.";
            } else {
                $message = "Error: Event submission failed. Please try again.";
            }
        }

        include '../views/submit_event.php';
    }
}