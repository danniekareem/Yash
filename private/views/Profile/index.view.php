<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <h3 class="fw-bold mb-3">My Profile</h3>

        <!-- Alerts -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">

            <!-- Profile Info -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        Account Information
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?= esc($user->fullname) ?></p>
                        <p><strong>Email:</strong> <?= esc($user->email) ?></p>
                        <p><strong>Role:</strong> <?= ucfirst($user->role) ?></p>
                        <p><strong>Branch:</strong> <?= esc($_SESSION['user']['branch_name']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        Change Password
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= ROOT ?>/profile/changePassword">

                            <div class="mb-3">
                                <label class="form-label">Old Password</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>

                            <button class="btn btn-dark">
                                🔒 Update Password
                            </button>

                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<?php $this->view('includes/footer'); ?>