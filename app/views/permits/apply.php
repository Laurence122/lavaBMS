<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include_once(APP_DIR . 'views/includes/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Permit Application</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form action="<?= site_url('permits/apply') ?>" method="post">
                        <div class="form-group">
                            <label for="permit_type">Permit Type</label>
                            <input type="text" name="permit_type" id="permit_type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="business_name">Business Name</label>
                            <input type="text" name="business_name" id="business_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="business_address">Business Address</label>
                            <textarea name="business_address" id="business_address" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="purpose">Purpose</label>
                            <textarea name="purpose" id="purpose" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once(APP_DIR . 'views/includes/footer.php'); ?>
