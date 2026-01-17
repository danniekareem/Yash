<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold">Manager Dashboard</h3>
                <small class="text-muted">System overview & performance</small>
            </div>

            <!-- Branch Filter -->
            <form method="get" class="d-flex align-items-center">
                <label class="me-2 fw-semibold">Filter by Branch:</label>
                <select name="branch_id" class="form-select me-2">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $b): ?>
                        <option value="<?= $b->id ?>" <?= ($selected_branch == $b->id) ? 'selected' : '' ?>>
                            <?= esc($b->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-primary btn-sm">Apply</button>
            </form>
        </div>

        <!-- KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Students</small>
                        <h4 class="fw-bold"><?= $stats['total_students'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Active Students</small>
                        <h4 class="fw-bold text-success"><?= $stats['active_students'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Revenue</small>
                        <h4 class="fw-bold">MK <?= number_format($stats['total_paid'] ?? 0) ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Outstanding Balance</small>
                        <h4 class="fw-bold text-warning">MK <?= number_format($stats['outstanding'] ?? 0) ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Per Category -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                Revenue per Category
            </div>

            <div class="card-body table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Total Fees</th>
                            <th>Total Paid</th>
                            <th>Outstanding</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($revenueByCategory as $c): ?>
                            <tr>
                                <td class="fw-semibold"><?= esc($c->category) ?></td>
                                <td>MK <?= number_format($c->total_fees ?? 0) ?></td>
                                <td class="text-success">MK <?= number_format($c->total_paid ?? 0) ?></td>
                                <td class="text-warning">MK <?= number_format($c->outstanding ?? 0) ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($revenueByCategory)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No data available
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Branch Performance Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                Branch Performance
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Branch</th>
                            <th>Total Students</th>
                            <th>Active Students</th>
                            <th>Total Fees</th>
                            <th>Total Paid</th>
                            <th>Outstanding</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($branchPerformance as $b): ?>
                            <tr>
                                <td><?= esc($b->branch_name) ?></td>
                                <td><?= $b->total_students ?? 0 ?></td>
                                <td><?= $b->active_students ?? 0 ?></td>
                                <td>MK <?= number_format($b->total_fees ?? 0) ?></td>
                                <td>MK <?= number_format($b->total_paid ?? 0) ?></td>
                                <td class="text-warning">MK <?= number_format($b->outstanding ?? 0) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
                                    <span class="badge bg-<?= ($s->status ?? '') === 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($s->status ?? 'inactive') ?>
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
