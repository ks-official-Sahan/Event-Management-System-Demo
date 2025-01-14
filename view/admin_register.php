<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center p-4">
    <div class="col-md-6">
        <h2>Admin Registration</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo ($result) ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form id="adminRegisterForm" method="post" action="/admin/register">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required />
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required />
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required />
            </div>
            <div class="mb-3">
                <label for="cPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="cPassword" name="cPassword" required />
            </div>
            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobile" name="mobile" required />
            </div>
            <button type="submit" class="btn btn-primary" name="register_admin">Register Admin</button>
        </form>
    </div>
</div>