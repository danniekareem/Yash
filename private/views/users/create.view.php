<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <div class="mb-4">
            <h4 class="fw-bold">Create User</h4>
            <small class="text-muted">Add manager or receptionist</small>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">-- Select Role --</option>
                                <option value="management">Manager</option>
                                <option value="receptionist">Receptionist</option>
                            </select>
                        </div>

                        <div class="col-md-6 d-none" id="branchWrapper">
                            <label class="form-label">Branch</label>
                            <select name="branch_id" class="form-select">
                                <option value="">-- Select Branch --</option>
                                <?php foreach ($branches as $b): ?>
                                    <option value="<?= $b->id ?>">
                                        <?= esc($b->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <button class="btn btn-primary px-4">
                            Create User
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        const branch = document.getElementById('branchWrapper');
        if (this.value === 'receptionist') {
            branch.classList.remove('d-none');
        } else {
            branch.classList.add('d-none');
        }
    });
</script>

<?php $this->view('includes/footer'); ?>