<?php $this->view('includes/header'); ?>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php $this->view('includes/sidebar'); ?>

        <!-- MAIN CONTENT -->
        <main class="col-md-10 ms-sm-auto px-md-4" style="min-height:100vh; background-color:#f4f6f8;">

            <?php $this->view('includes/navbar'); ?>

            <div class="mt-4">

                <!-- Dashboard Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-primary">Manager Dashboard</h2>
                    <small class="text-muted"><?= date('l, d M Y') ?></small>
                </div>

                <!-- Summary Cards -->
                <div class="row g-4">
                    <?php
                    $cardData = [
                        ['title' => 'Total Students', 'value' => count($students), 'bg' => 'primary', 'icon' => 'bi-people-fill'],
                        ['title' => 'Total Paid', 'value' => "MK " . number_format($totalPaid), 'bg' => 'success', 'icon' => 'bi-cash-stack'],
                        ['title' => 'Pending Payments', 'value' => "MK " . number_format($totalPending), 'bg' => 'warning', 'icon' => 'bi-exclamation-circle'],
                        ['title' => 'Branches', 'value' => count($branches), 'bg' => 'info', 'icon' => 'bi-building']
                    ];
                    foreach ($cardData as $card):
                    ?>
                        <div class="col-md-3">
                            <div class="card shadow-sm border-0 h-100 hover-shadow">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 fs-2 text-<?= $card['bg'] ?>">
                                        <i class="bi <?= $card['icon'] ?>"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1"><?= $card['title'] ?></h6>
                                        <h4 class="fw-bold"><?= $card['value'] ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Charts -->
                <div class="row mt-5 g-4">
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-primary text-white fw-bold">
                                Students per Branch
                            </div>
                            <div class="card-body">
                                <canvas id="branchChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-success text-white fw-bold">
                                Payments per Category
                            </div>
                            <div class="card-body">
                                <canvas id="categoryChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Latest Students Table -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <span>Latest Students</span>
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-striped table-hover table-bordered align-middle text-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Full Name</th>
                                            <th>Category</th>
                                            <th>Branch</th>
                                            <th>Course</th>
                                            <th>Fees</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $i => $s):
                                            $balance = max(0, $s->fees - $s->paid);
                                        ?>
                                            <tr>
                                                <td><?= $i + 1 ?></td>
                                                <td class="text-start ps-3"><?= esc($s->fullname) ?></td>
                                                <td><?= esc($s->category) ?></td>
                                                <td><?= esc($s->branch_name) ?></td>
                                                <td><?= esc($s->course_type) ?></td>
                                                <td>MK <?= number_format($s->fees) ?></td>
                                                <td>MK <?= number_format($s->paid) ?></td>
                                                <td>MK <?= number_format($balance) ?></td>
                                                <td>
                                                    <?php if ($balance == 0): ?>
                                                        <span class="badge bg-success">Paid</span>
                                                    <?php elseif ($s->paid > 0): ?>
                                                        <span class="badge bg-info">Partial</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($students)): ?>
                                            <tr>
                                                <td colspan="9" class="text-muted">No students found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const branchChart = new Chart(document.getElementById('branchChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($branches, 'name')) ?>,
            datasets: [{
                label: 'Students',
                data: <?= json_encode(array_column($branches, 'total_students')) ?>,
                backgroundColor: ['#0d6efd', '#6610f2', '#198754', '#ffc107'],
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const categoryChart = new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($categories, 'category')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($categories, 'count')) ?>,
                backgroundColor: ['#0d6efd', '#198754', '#ffc107'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<!-- Custom Styles -->
<style>
    /* Sidebar sticky & hover effect */
    .sidebar {
        position: sticky;
        top: 0;
        height: 100vh;
        background: #343a40;
        color: #fff;
    }

    .sidebar a {
        color: #adb5bd;
        transition: all 0.2s;
    }

    .sidebar a:hover {
        color: #fff;
        background: #495057;
        border-radius: 5px;
    }

    /* Card hover shadow */
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease-in-out;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    /* Table stripes & hover */
    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }

    .table-hover tbody tr:hover {
        background-color: #e9ecef;
    }
</style>

<?php $this->view('includes/footer'); ?>