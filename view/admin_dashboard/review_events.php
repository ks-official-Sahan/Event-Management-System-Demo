<!DOCTYPE html>
<html>

<head>
    <title>Review Pending Events</title>
</head>

<body>
    <h2>Pending Events</h2>

    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (count($pendingEvents) > 0): ?>
        <ul>
            <?php foreach ($pendingEvents as $event): ?>
                <li>
                    <h3><?php echo $event['title']; ?></h3>
                    <p><?php echo $event['description']; ?></p>
                    <p>Start Date: <?php echo $event['start_date']; ?></p>
                    <p>End Date: <?php echo $event['end_date']; ?></p>
                    <p>Location: <?php echo $event['location']; ?></p>

                    <form method="post" action="/admin/review-events">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" name="approve_event">Approve</button>
                        <button type="submit" name="reject_event">Reject</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No pending events to review.</p>
    <?php endif; ?>
</body>

</html>