<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center p-4">
    <div class="col-md-6">
        <h2>Login</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo (strpos($message, 'successful') !== false) ? 'success' : 'danger'; ?>"
                role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="post" action="/login">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required />
            </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>
    </div>
</div>