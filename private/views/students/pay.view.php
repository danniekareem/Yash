<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="container mt-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= ROOT ?>/students">Students</a></li>
            <li class="breadcrumb-item active" aria-current="page">Record Payment</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <!-- Student Info Card -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Student: <?= esc($student->fullname) ?></h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item"><strong>Email:</strong> <?= esc($student->email) ?></li>
                        <li class="list-group-item"><strong>Phone:</strong> <?= esc($student->phone) ?></li>
                        <li class="list-group-item"><strong>Category:</strong> <?= esc($student->category) ?></li>
                        <li class="list-group-item"><strong>Course:</strong> <?= esc($student->course_type) ?></li>
                        <li class="list-group-item"><strong>Boarding:</strong> <?= esc($student->boarding) ?></li>
                        <li class="list-group-item"><strong>Fees:</strong> MK <?= number_format($student->fees) ?></li>
                    </ul>

                    <!-- Payment Form -->
                    <form method="POST" class="mt-3">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Payment Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" required
                                value="<?= esc($student->fees) ?>">
                        </div>
                        <button class="btn btn-success w-100">Submit Payment</button>
                    </form>

                    <a href="<?= ROOT ?>/students" class="btn btn-secondary w-100 mt-3">Back to Student List</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->view('includes/footer'); ?>