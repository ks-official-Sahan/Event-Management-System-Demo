<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management App</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">Event Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/eventsys/admin/review-events">Review Events</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/eventsys/admin/events">Event List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/eventsys/admin/users">User List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/eventsys/logout">Logout</a>
                        </li>
                    <?php } else if (isset($_SESSION['user_id'])) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/eventsys/view/submit_event.php">Submit Event</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="/eventsys/view/my-events">My Events</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" href="/eventsys/view/profile.php">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/eventsys/logout">Logout</a>
                            </li>
                    <?php } else {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/eventsys/view/register.php">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/eventsys/view/login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/eventsys/view/admin_login.php">Admin</a>
                            </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        // The content of other views will be included here
        ?>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>

</html>