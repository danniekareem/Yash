<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <div class="mb-4">
            <h3 class="fw-bold">Payments</h3>
            <small class="text-muted">Manage student payments</small>
        </div>

        <!-- Search -->
        <form method="GET" class="row mb-3">
            <div class="col-md-10">
                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search student name or phone"
                    value="<?= esc($search) ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <div class="card border-0 shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Fees</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($students): ?>
                            <?php foreach ($students as $s): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= esc($s->fullname) ?></div>
                                        <small class="text-muted"><?= esc($s->phone) ?></small>
                                    </td>

                                    <td>MK <?= number_format($s->fees) ?></td>
                                    <td>MK <?= number_format($s->paid) ?></td>
                                    <td>MK <?= number_format($s->balance) ?></td>

                                    <td>
                                        <?php if ($s->balance <= 0): ?>
                                            <span class="badge bg-success">Fully Paid</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <button class="btn btn-sm btn-outline-success me-2"
                                            <?= $s->balance <= 0 ? 'disabled' : '' ?>
                                            data-bs-toggle="modal"
                                            data-bs-target="#payModal<?= $s->id ?>">
                                            💳 Pay
                                        </button>

                                        <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#historyModal<?= $s->id ?>">
                                            📜 History
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No students found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="<?= ROOT ?>/payments?page=<?= $i ?>&search=<?= esc($search) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>

        <!-- ================= MODALS (OUTSIDE TABLE) ================= -->
        <?php foreach ($students as $s): ?>

            <!-- PAY MODAL -->
            <div class="modal fade" id="payModal<?= $s->id ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <form method="POST"
                            action="<?= ROOT ?>/students/pay/<?= $s->id ?>"
                            enctype="multipart/form-data">

                            <div class="modal-header">
                                <h5 class="modal-title">Pay: <?= esc($s->fullname) ?></h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Amount</label>
                                    <input type="number"
                                        name="amount"
                                        class="form-control"
                                        max="<?= $s->balance ?>"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label>Payment Date</label>
                                    <input type="date"
                                        name="payment_date"
                                        class="form-control"
                                        value="<?= date('Y-m-d') ?>"
                                        max="<?= date('Y-m-d') ?>"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label>Receipt</label>
                                    <input type="file"
                                        name="receipt"
                                        class="form-control"
                                        accept="image/*"
                                        required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-success">Submit</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- HISTORY MODAL -->
            <div class="modal fade" id="historyModal<?= $s->id ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Payment History: <?= esc($s->fullname) ?></h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <?php if (!empty($s->payments)): ?>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($s->payments as $p): ?>
                                            <tr>
                                                <td><?= esc($p->payment_date) ?></td>
                                                <td>MK <?= number_format($p->amount) ?></td>
                                                <td>
                                                    <?php if ($p->receipt): ?>
                                                        <a href="<?= ROOT ?>/<?= $p->receipt ?>" target="_blank">
                                                            View
                                                        </a>
                                                    <?php else: ?>
                                                        —
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">No payments yet.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>
</div>

<?php $this->view('includes/footer'); ?>