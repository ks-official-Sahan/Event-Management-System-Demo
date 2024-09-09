<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center p-4">
    <div class="col-md-6">
        <h2>My Profile</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo (strpos($message, 'successful') !== false) ? 'success' : 'danger'; ?>"
                role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userData = $_SESSION["user"];
        ?>

        <h3>Welcome, <?php echo $userData['username']; ?>!</h3>

        <form method="post" action="/eventsys/profile/update">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    value="<?php echo $userData['first_name']; ?>" required />
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                    value="<?php echo $userData['last_name']; ?>" required />
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo $userData['username']; ?>" required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo $userData['email']; ?>" required />
            </div>
            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobile" name="mobile"
                    value="<?php echo $userData['mobile_no']; ?>" required />
            </div>
            <button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button>
        </form>
    </div>
</div>