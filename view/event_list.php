<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2>All Events</h2>

        <?php if (count($allEvents) > 0): ?>
            <ul class="list-group gap-4 g-2 py-4">
                <?php foreach ($allEvents as $event): ?>
                    <li class="list-group-item <?php echo($event['status']=='approved' ? 'bg-success' : ($event['status']=='pending'? 'bg-warning' : 'bg-danger')); ?>">
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
                        <p>Status: <?php echo $event['status']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No events found.</p>
        <?php endif; ?>
    </div>
</div>