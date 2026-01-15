<?php $this->view('includes/header'); ?>
<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Add Student</h3>
                <small class="text-muted">Fill in student details</small>
            </div>
            <a href="<?= ROOT ?>/students" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Students
            </a>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" id="addStudentForm">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" max="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" id="category" class="form-select" required>
                                <option value="C1">C1</option>
                                <option value="B">B</option>
                                <option value="A1">A1</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Course Type</label>
                            <select name="course_type" id="course_type" class="form-select" required></select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Boarding</label>
                            <select name="boarding" id="boarding" class="form-select">
                                <option value="No">No</option>
                                <option value="Yes">Yes (+30k)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Calculated Fee</label>
                            <input type="text" id="calculated_fee" class="form-control fw-bold text-primary" readonly>
                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <button class="btn btn-primary px-4">Add Student</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    const prices = {
        C1: {
            Full: 703000,
            Half: 650000,
            "30 days": 500000,
            "15 days": 0
        },
        B: {
            Full: 653000,
            Half: 600000,
            "30 days": 500000,
            "15 days": 0
        },
        A1: {
            Full: 303000
        }
    };

    const categoryEl = document.getElementById('category');
    const courseTypeEl = document.getElementById('course_type');
    const boardingEl = document.getElementById('boarding');
    const feeEl = document.getElementById('calculated_fee');

    function populateCourseTypes() {
        const cat = categoryEl.value;
        const courses = Object.keys(prices[cat]);
        courseTypeEl.innerHTML = "";
        courses.forEach(c => {
            const option = document.createElement('option');
            option.value = c;
            option.text = c;
            courseTypeEl.appendChild(option);
        });
        calculateFee();
    }

    function calculateFee() {
        const cat = categoryEl.value;
        const course = courseTypeEl.value;
        let fee = prices[cat][course] || 0;
        if (boardingEl.value.toLowerCase() === 'yes') fee += 30000;
        feeEl.value = "MK " + fee.toLocaleString();
    }

    categoryEl.addEventListener('change', populateCourseTypes);
    courseTypeEl.addEventListener('change', calculateFee);
    boardingEl.addEventListener('change', calculateFee);

    populateCourseTypes();
</script>

<?php $this->view('includes/footer'); ?>