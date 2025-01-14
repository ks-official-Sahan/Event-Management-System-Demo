<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center p-4">
    <div class="col-md-6">
        <h2>My Admin Profile</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo (strpos($message, 'successful') !== false) ? 'success' : 'danger'; ?>"
                role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <h3>Welcome, <?php echo $adminData['username']; ?>!</h3>

        <form class="profile-form" method="post" action="/admin/profile/update">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control profile-field" id="first_name" name="first_name"
                    value="<?php echo $adminData['first_name']; ?>" readonly required />
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control profile-field" id="last_name" name="last_name"
                    value="<?php echo $adminData['last_name']; ?>" readonly required />
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control profile-field" id="username" name="username"
                    value="<?php echo $adminData['username']; ?>" readonly required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control profile-field" id="email" name="email"
                    value="<?php echo $adminData['email']; ?>" readonly required />
            </div>
            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control profile-field" id="mobile" name="mobile"
                    value="<?php echo $adminData['mobile_no']; ?>" readonly required />
            </div>

            <button type="button" class="btn btn-secondary edit-profile-btn">Edit Profile</button>
            <button type="submit" class="btn btn-primary save-profile-btn" style="display: none;">Save Changes</button>
        </form>
    </div>
</div>