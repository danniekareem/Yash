<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Yash</span>

        <div class="d-flex align-items-center">
            <span class="text-light me-3">
                Welcome, <?= esc($_SESSION['user']['name'] ?? 'Guest') ?>!
            </span>
            <span class="text-light me-3">
                Branch: <?= esc($_SESSION['user']['branch_name'] ?? 'Unknown') ?>
            </span>
            <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    👤 User
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-item text-muted">
                        Branch: <?= esc($_SESSION['user']['branch_name'] ?? 'Unknown') ?>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="<?= ROOT ?>/profile">Profile</a></li>
                    <li><a class="dropdown-item text-danger" href="<?= ROOT ?>/auth/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>