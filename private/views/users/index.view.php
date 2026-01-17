<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">

    <!-- Sidebar -->
    <?php $this->view('includes/sidebar'); ?>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">User Management</h4>
                <small class="text-muted">Manage system users</small>
            </div>

            <a href="<?= ROOT ?>/users/create" class="btn btn-primary">
                + Add User
            </a>
        </div>

        <!-- Users Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= esc($u->fullname) ?></td>
                                    <td><?= esc($u->email) ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?= ucfirst($u->role) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($u->branch_name ?? 'All') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $u->status === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($u->status) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= ROOT ?>/users/edit/<?= $u->id ?>" class="btn btn-sm btn-outline-warning">
                                            ✏️ Edit
                                        </a>
                                        <!-- Activate / Deactivate -->
                                        <a href="<?= ROOT ?>/users/toggleStatus/<?= $u->id ?>"
                                            class="btn btn-sm <?= $u->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' ?>"
                                            onclick="return confirm('Are you sure?')">
                                            <?= $u->status === 'active' ? 'Deactivate' : 'Activate' ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No users found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

<?php $this->view('includes/footer'); ?>