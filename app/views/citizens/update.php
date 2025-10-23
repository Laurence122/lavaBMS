<?php
// app/views/citizens/update.php

if (file_exists(APP_DIR . 'views/includes/header.php')) {
    include APP_DIR . 'views/includes/header.php';
}

?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>Edit Citizen</h2>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/citizens/update/<?php echo $citizen['id']; ?>" method="post">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($citizen['first_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($citizen['last_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($citizen['middle_name']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($citizen['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($citizen['phone']); ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address"><?php echo htmlspecialchars($citizen['address']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="birth_date">Birth Date</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($citizen['birth_date']); ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="male" <?php echo ($citizen['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($citizen['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo ($citizen['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="national_id">National ID</label>
                    <input type="text" class="form-control" id="national_id" name="national_id" value="<?php echo htmlspecialchars($citizen['national_id'] ?? ''); ?>" placeholder="Enter your National ID for verification">
                    <small class="form-text text-muted">Required for document requests and permit applications.</small>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="<?php echo BASE_URL; ?>/citizens" class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
</div>

<?php
if (file_exists(APP_DIR . 'views/includes/footer.php')) {
    include APP_DIR . 'views/includes/footer.php';
}
?>
