<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Welcome, <?= esc($_SESSION['user']['name'] ?? 'Receptionist') ?></h3>
                <small class="text-muted">Your daily overview</small>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row g-3">

            <!-- Total Students Card -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted">Total Students</h6>
                            <h2 class="fw-bold"><?= $totalStudents ?? 0 ?></h2>
                        </div>
                        <a href="<?= ROOT ?>/students" class="btn btn-primary mt-3">
                            <i class="bi bi-people me-1"></i> Manage Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Add Student Card -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted">Add Student</h6>
                            <h2 class="fw-bold">➕</h2>
                        </div>
                        <a href="<?= ROOT ?>/students/add" class="btn btn-success mt-3">
                            <i class="bi bi-person-plus me-1"></i> Add New
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payments Card -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted">Total Collected</h6>
                            <h2 class="fw-bold">MK <?= number_format($totalPaid ?? 0) ?></h2>
                        </div>
                        <a href="<?= ROOT ?>/payments" class="btn btn-warning mt-3">
                            <i class="bi bi-cash-stack me-1"></i> View Payments
                        </a>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>

<?php $this->view('includes/footer'); ?>