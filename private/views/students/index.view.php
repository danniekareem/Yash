<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Students</h3>
                <small class="text-muted">Manage students and payments</small>
            </div>

            <a href="<?= ROOT ?>/students/add" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Student
            </a>
        </div>

        <!-- Search & Filter -->
        <form class="row mb-3" method="GET">
            <div class="col-md-4">
                <select name="search_field" class="form-select">
                    <option value="fullname" <?= $searchField == 'fullname' ? 'selected' : '' ?>>Name</option>
                    <option value="start_date" <?= $searchField == 'start_date' ? 'selected' : '' ?>>Start Date</option>
                    <option value="category" <?= $searchField == 'category' ? 'selected' : '' ?>>Category</option>
                    <option value="phone" <?= $searchField == 'phone' ? 'selected' : '' ?>>Phone</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="search_value" class="form-control" placeholder="Search..." value="<?= esc($searchValue) ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Search</button>
            </div>
        </form>

        <!-- Students Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body table-responsive">

                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Category</th>
                            <th>Course</th>
                            <th>Branch</th>
                            <th>Fees</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $s):
                                $paid = $s->paid ?? 0;
                                $balance = $s->fees - $paid;
                            ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= esc($s->fullname) ?></div>
                                        <small class="text-muted"><?= esc($s->phone) ?></small>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= esc($s->category) ?></span></td>
                                    <td><?= esc($s->course_type) ?></td>
                                    <td><?= esc($s->branch_name) ?></td>
                                    <td>
                                        MK <?= number_format($s->fees) ?><br>
                                        <small class="text-muted">Paid: MK <?= number_format($paid) ?></small>
                                    </td>
                                    <td>
                                        <?php if ($balance <= 0): ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Balance MK <?= number_format($balance) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <!-- View Button -->
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewStudentModal<?= $s->id ?>">
                                            👁 View
                                        </button>

                                        <!-- Pay Button -->
                                        <button class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#payStudentModal<?= $s->id ?>">
                                            💳 Pay
                                        </button>
                                    </td>
                                </tr>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewStudentModal<?= $s->id ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title"><?= esc($s->fullname) ?> Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6"><strong>DOB:</strong> <?= esc($s->dob) ?></div>
                                                    <div class="col-md-6"><strong>Start Date:</strong> <?= esc($s->start_date) ?></div>
                                                    <div class="col-md-6"><strong>Email:</strong> <?= esc($s->email) ?></div>
                                                    <div class="col-md-6"><strong>Phone:</strong> <?= esc($s->phone) ?></div>
                                                    <div class="col-md-6"><strong>Category:</strong> <?= esc($s->category) ?></div>
                                                    <div class="col-md-6"><strong>Course:</strong> <?= esc($s->course_type) ?></div>
                                                    <div class="col-md-6"><strong>Boarding:</strong> <?= esc($s->boarding) ?></div>
                                                    <div class="col-md-6"><strong>Branch:</strong> <?= esc($s->branch_name) ?></div>
                                                    <div class="col-md-6"><strong>Fees:</strong> MK <?= number_format($s->fees) ?></div>
                                                    <div class="col-md-6"><strong>Paid:</strong> MK <?= number_format($paid) ?></div>
                                                    <div class="col-md-6"><strong>Balance:</strong> MK <?= number_format($balance) ?></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pay Modal -->
                                <div class="modal fade" id="payStudentModal<?= $s->id ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="<?= ROOT ?>/students/pay/<?= $s->id ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Make Payment: <?= esc($s->fullname) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Total Fees</label>
                                                        <input type="text" class="form-control" value="MK <?= number_format($s->fees) ?>" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Amount to Pay</label>
                                                        <input type="number" name="amount" class="form-control" min="1" max="<?= $balance ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Payment Date</label>
                                                        <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Submit Payment</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No students found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mt-3">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= ROOT ?>/students?page=<?= $i ?>&search_field=<?= $searchField ?>&search_value=<?= $searchValue ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer'); ?>