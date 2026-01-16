<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <!-- Header -->
        <div class="mb-4">
            <h3 class="fw-bold">Manager Dashboard</h3>
            <small class="text-muted">System overview & performance</small>
        </div>

        <!-- KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Students</small>
                        <h4 class="fw-bold"><?= $stats['total_students'] ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Active Students</small>
                        <h4 class="fw-bold text-success"><?= $stats['active_students'] ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Revenue</small>
                        <h4 class="fw-bold">MK <?= number_format($stats['total_paid']) ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Outstanding Balance</small>
                        <h4 class="fw-bold text-warning">MK <?= number_format($stats['outstanding']) ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Students -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                Recent Students
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Branch</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $s): ?>
                            <tr>
                                <td><?= esc($s->fullname) ?></td>
                                <td><?= esc($s->category) ?></td>
                                <td><?= esc($s->branch_name ?? '—') ?></td>
                                <td>
                                    <span class="badge bg-<?= $s->status === 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($s->status) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php $this->view('includes/footer'); ?>