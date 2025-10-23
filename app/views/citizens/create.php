<?php
// app/views/citizens/create.php

if (file_exists(APP_DIR . 'views/includes/header.php')) {
    include APP_DIR . 'views/includes/header.php';
}

?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>Add New Citizen</h2>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="<?php echo DOMAIN; ?>/citizens/create" method="post">
                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="number" class="form-control" id="user_id" name="user_id" required>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address"></textarea>
                </div>
                <div class="form-group">
                    <label for="birth_date">Birth Date</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="<?php echo DOMAIN; ?>/citizens" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
</div>

<?php
if (file_exists(APP_DIR . 'views/includes/footer.php')) {
    include APP_DIR . 'views/includes/footer.php';
}
?>
