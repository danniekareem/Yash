<?php $this->view('includes/header'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 offset-md-4">

            <h3 class="text-center mb-4">Login</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= ROOT ?>/Login/login">

                <div class="mb-3">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required>
                </div>

                <button class="btn btn-primary w-100">
                    Login
                </button>

            </form>

        </div>
    </div>
</div>

<?php $this->view('includes/footer'); ?>