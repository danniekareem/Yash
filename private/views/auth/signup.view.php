<?php $this->view('includes/header'); ?>

<style>
    body {
        background: linear-gradient(to right, #4facfe, #00f2fe);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        width: 100%;
        max-width: 400px;
    }

    .login-card h3 {
        font-weight: 700;
        color: #333;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #4facfe;
    }

    .login-btn {
        background: #4facfe;
        border: none;
    }

    .login-btn:hover {
        background: #00f2fe;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon input {
        padding-left: 40px;
    }
</style>

<div class="login-card">

    <h3 class="text-center mb-4">ChikondiG Driving School</h3>
    <p class="text-center text-muted mb-4">Login to your account</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= ROOT ?>/Login/login">

        <div class="mb-3 input-with-icon">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="mb-3 input-with-icon">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <button class="btn login-btn w-100 py-2 text-white fw-bold">Login</button>

    </form>

    <p class="text-center text-muted mt-3 mb-0">
        &copy; <?= date('Y') ?> ChikondiG Driving School
    </p>
</div>

<?php $this->view('includes/footer'); ?>