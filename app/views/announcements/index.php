<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include_once(APP_DIR . 'views/includes/header.php'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Announcements</h1>

    <a href="/announcements/create" class="btn btn-primary mb-4">Create Announcement</a>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($announcements as $announcement) : ?>
                            <tr>
                                <td><?= html_escape($announcement['title']) ?></td>
                                <td><?= html_escape($announcement['created_at']) ?></td>
                                <td>
                                    <a href="/announcements/edit/<?= $announcement['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                    <a href="/announcements/delete/<?= $announcement['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once(APP_DIR . 'views/includes/footer.php'); ?>
