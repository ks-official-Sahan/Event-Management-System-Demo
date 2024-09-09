<?php
// Include the base layout
include 'layout.php';
?>

<div class="row justify-content-center p-4">
    <div class="col-md-8">
        <h2>User List</h2>

        <ul class="list-group gap-4 g-2 py-4">
            <?php foreach ($users as $user) { ?>
                <li class="list-group-item">
                    <a href="/eventsys/admin/users/<?php echo $user['id']; ?>/profile" class="text-decoration-none text-dark">
                        <div>
                            <div class="mb-3">
                                <span class="text-lg">Username: </span>
                                <span class="text-primary text-lg"><?php echo $user['username']; ?></span>
                            </div>
                            <div class="mb-3">
                                <span class="text-lg">Email: </span>
                                <span class="text-primary text-lg"><?php echo $user['email']; ?></span>
                            </div>
                            <div class="mb-3">
                                <span class="text-lg">First Name: </span>
                                <span class="text-lg"><?php echo $user['first_name']; ?></span>
                            </div>
                            <div class="mb-3">
                                <span class="text-lg">Last Name: </span>
                                <span class="text-lg"><?php echo $user['last_name']; ?></span>
                            </div>
                        </div>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>