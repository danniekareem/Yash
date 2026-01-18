<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">
        <div class="card shadow-sm border-0 col-md-6">
            <div class="card-header bg-dark text-white">
                Edit Branch
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Branch Name</label>
                        <input type="text" name="name" class="form-control"
                            value="<?= esc($branch->name) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= $branch->status === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $branch->status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-success">Update</button>
                    <a href="<?= ROOT ?>/branches" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer'); ?>