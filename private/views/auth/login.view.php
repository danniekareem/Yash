<?php $this->view('includes/header'); ?>

<style>
    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4f8;
    }

    .login-container {
        display: flex;
        flex-wrap: wrap;
        width: 900px;
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .login-left {
        flex: 1;
        background: url('<?= ROOT ?>/assets/yash.jpeg') center/cover no-repeat;
        min-height: 400px;
    }

    .login-right {
        flex: 1;
        padding: 40px 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-right h3 {
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .login-right p {
        color: #777;
        margin-bottom: 30px;
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

    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
            width: 100%;
            border-radius: 0;
        }

        .login-left {
            height: 200px;
        }
    }
</style>

<div class="login-container">

    <!-- Left Side: Illustration -->
    <div class="login-left"></div>

    <!-- Right Side: Login Form -->
    <div class="login-right">

        <h3>LOGIN</h3>


        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= ROOT ?>/Auth/login">

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

        <p class="text-center text-muted mt-4 mb-0">
            &copy; <?= date('Y') ?> Yash Driving School
        </p>
    </div>

</div>