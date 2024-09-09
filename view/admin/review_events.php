<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center p-4">
    <div class="col-md-8">
        <h2>Pending Events</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo (strpos($message, 'approved') !== false || strpos($message, 'rejected') !== false) ? 'success' : 'danger'; ?>"
                role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (count($pendingEvents) > 0): ?>
            <ul class="list-group">
                <?php foreach ($pendingEvents as $event): ?>
                    <li class="list-group-item">
                        <h3><?php echo $event['title']; ?></h3>
                        <p><?php echo $event['description']; ?></p>
                        <p>Start Date: <?php echo $event['event_date']; ?></p>
                        <p>Time: <?php echo $event['event_time']; ?></p>
                        <p>Number of Guests: <?php echo $event['num_guests']; ?></p>
                        <p>Add-ons: <?php echo $event['addons']; ?></p>
                        <p>Contact Name: <?php echo $event['contact_name']; ?></p>
                        <p>Contact Email: <?php echo $event['contact_email']; ?></p>
                        <p>Contact Phone: <?php echo $event['contact_phone']; ?></p>
                        <p>Special Requests: <?php echo $event['special_requests']; ?></p>

                        <form method="post" action="/admin/review-events">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" name="approve_event" class="btn btn-success">Approve</button>
                            <button type="submit" name="reject_event" class="btn btn-danger">Reject</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No pending events to review.</p>
        <?php endif; ?>
    </div>
</div>