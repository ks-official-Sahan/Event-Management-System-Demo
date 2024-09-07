<?php
class Event
{
    public static function create($categoryId, $title, $description, $startDate, $endDate, $location)
    {
        $data = [
            'category_id' => $categoryId,
            'title' => $title,
            'description' => $description,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $location,
            'status' => 'pending'
        ];
        return Database::getInstance()->insert('events', $data);
    }

    public static function getPendingEvents()
    {
        return Database::getInstance()->search('events', ['*'], "status = 'pending'");
    }

    public static function approveEvent($eventId)
    {
        return Database::getInstance()->update('events', ['status' => 'approved'], "id = $eventId");
    }

    public static function rejectEvent($eventId)
    {
        return Database::getInstance()->update('events', ['status' => 'rejected'], "id = $eventId");
    }

    // ... other Event model methods ...
}