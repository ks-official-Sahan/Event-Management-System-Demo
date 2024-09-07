<!DOCTYPE html>
<html>

<head>
    <title>Submit Event</title>
</head>

<body>
    <h2>Submit New Event</h2>

    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" action="/submit-event">
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
            <?php
            // Fetch event categories from the database and populate the dropdown
            $categories = Database::getInstance()->search('event_categories', ['id', 'name']);
            foreach ($categories as $category) {
                echo "<option value='{$category['id']}'>{$category['name']}</option>";
            }
            ?>
        </select><br>

        <input type="text" name="title" placeholder="Event Title" required><br>
        <textarea name="description" placeholder="Event Description"></textarea><br>
        <input type="datetime-local" name="start_date" required><br>
        <input type="datetime-local" name="end_date" required><br>
        <input type="text" name="location" placeholder="Location"><br>

        <button type="submit" name="submit_event">Submit Event</button>
    </form>
</body>

</html>