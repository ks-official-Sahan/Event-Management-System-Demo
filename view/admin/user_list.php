<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center p-4">
    <div class="col-md-8">
        <h2>User List</h2>

        <ul class="list-group">
            <?php foreach ($users as $user) { ?>
                <li class="list-group-item">
                    <a href="/admin/users/<?php echo $user['id']; ?>/profile"><?php echo $user['username']; ?></a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>