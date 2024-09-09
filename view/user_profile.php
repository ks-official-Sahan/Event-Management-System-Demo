<link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center p-4">
    <div class="col-md-6">
        <h2>User Profile</h2>

        <?php if (isset($message)) { ?>
            <div class="alert alert-<?php echo (strpos($message, 'successful') !== false) ? 'success' : 'danger'; ?>"
                role="alert">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <h3><?php echo $userData['username']; ?></h3>

        <form class="profile-form" method="post" action="/admin/users/<?php echo $userData['id']; ?>/profile/update">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control profile-field" id="first_name" name="first_name"
                    value="<?php echo $userData['first_name']; ?>" readonly required />
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control profile-field" id="last_name" name="last_name"
                    value="<?php echo $userData['last_name']; ?>" readonly required />
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control profile-field" id="username" name="username"
                    value="<?php echo $userData['username']; ?>" readonly required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control profile-field" id="email" name="email"
                    value="<?php echo $userData['email']; ?>" readonly required />
            </div>
            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control profile-field" id="mobile" name="mobile"
                    value="<?php echo $userData['mobile_no']; ?>" readonly required />
            </div>

            <button type="button" class="btn btn-secondary edit-profile-btn">Edit Profile</button>
            <button type="submit" class="btn btn-primary save-profile-btn" style="display: none;">Save Changes</button>
        </form>
    </div>
</div>

<script src="../../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/js/script.js"></script>