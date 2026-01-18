<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">
        <div class="card shadow-sm border-0 col-md-6">
            <div class="card-header bg-dark text-white">
                Add Branch
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Branch Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <button class="btn btn-success">Save</button>
                    <a href="<?= ROOT ?>/branches" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer'); ?>