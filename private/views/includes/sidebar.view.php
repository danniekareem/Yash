<?php
$userRole = $_SESSION['user']['role'] ?? '';
?>

<style>
    .sidebar {
        width: 240px;
        min-height: 100vh;
        background: #1f2937;
        color: #d1d5db;
        position: sticky;
        top: 0;
    }

    .sidebar a {
        color: #d1d5db;
        text-decoration: none;
        padding: 10px 16px;
        display: block;
        border-radius: 8px;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .sidebar a:hover {
        background: #374151;
        color: #fff;
    }

    .sidebar .active {
        background: #2563eb;
        color: #fff;
    }

    .sidebar h6 {
        font-size: 11px;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #9ca3af;
        margin: 22px 16px 10px;
    }
</style>



<div class="sidebar p-3">
    <h5 class="mb-1">🎓 Yash</h5>

    <h6><?= $_SESSION['user']['role'] === 'manager' ? 'Management' : 'Reception' ?></h6>

    <a href="<?= ROOT ?>/students/dashboard">🏠 Dashboard</a>
    <a href="<?= ROOT ?>/students">👩‍🎓 Students</a>
    <a href="<?= ROOT ?>/payments">💳 Payments</a>


    <?php if ($_SESSION['user']['role'] === 'manager'): ?>
        <h6>Management</h6>
        <a href="<?= ROOT ?>/students">👩‍🎓 Students</a>
        <a href="<?= ROOT ?>/branches">🏢 Branches</a>
        <a href="<?= ROOT ?>/users">👥 Users</a>
        <a href="<?= ROOT ?>/payments">💳 Payments</a>
        <a href="<?= ROOT ?>/reports">📊 Reports</a>
    <?php endif; ?>

    <hr>
    <a href="<?= ROOT ?>/auth/logout" class="text-danger">🚪 Logout</a>
</div>