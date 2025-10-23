<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include_once(APP_DIR . 'views/includes/header.php'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create Role</h1>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="role_name">Role Name</label>
            <input type="text" name="role_name" id="role_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>

<?php include_once(APP_DIR . 'views/includes/footer.php'); ?>
