<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <!-- Page Header -->
        <div class="mb-4">
            <h3 class="fw-bold">Edit User</h3>
            <small class="text-muted">Update user role, branch and status</small>
        </div>

        <!-- Edit User Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <form method="POST">

                    <!-- Fullname -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input
                            type="text"
                            class="form-control"
                            name="fullname"
                            value="<?= esc($user->fullname) ?>"
                            required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            value="<?= esc($user->email) ?>"
                            required>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="management" <?= $user->role === 'management' ? 'selected' : '' ?>>
                                Manager
                            </option>
                            <option value="receptionist" <?= $user->role === 'receptionist' ? 'selected' : '' ?>>
                                Receptionist
                            </option>
                        </select>
                    </div>

                    <!-- Branch -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">All Branches</option>
                            <?php foreach ($branches as $b): ?>
                                <option
                                    value="<?= $b->id ?>"
                                    <?= $user->branch_id == $b->id ? 'selected' : '' ?>>
                                    <?= esc($b->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= $user->status === 'active' ? 'selected' : '' ?>>
                                Active
                            </option>
                            <option value="inactive" <?= $user->status === 'inactive' ? 'selected' : '' ?>>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="<?= ROOT ?>/users" class="btn btn-outline-secondary">
                            ← Back
                        </a>

                        <button class="btn btn-primary">
                            💾 Save Changes
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<?php $this->view('includes/footer'); ?>