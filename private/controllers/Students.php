<?php

class Students extends Controller
{
    private $studentModel;
    private $branchModel;
    private $branch_id;

    public function __construct()
    {
        require_auth();

        // Load models
        $this->studentModel = $this->load_model('Student');
        $this->branchModel  = $this->load_model('Branch');

        // If receptionist, store their branch id
        if ($_SESSION['user']['role'] === 'receptionist') {
            $this->branch_id = $_SESSION['user']['branch_id'];
        }
    }

    // Show all students
    public function index()
    {
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $filters = [];
        if (!empty($_GET['search_field']) && !empty($_GET['search_value'])) {
            $filters[$_GET['search_field']] = $_GET['search_value'];
        }

        // Restrict receptionist to their branch
        if ($_SESSION['user']['role'] === 'receptionist') {
            $filters['branch_id'] = $this->branch_id;
        }

        $students = $this->studentModel->getPaginated($limit, $offset, $filters);
        $totalStudents = $this->studentModel->countFiltered($filters);
        $totalPages = ceil($totalStudents / $limit);

        // Attach branch names
        foreach ($students as &$s) {
            $branch = $this->branchModel->findById($s->branch_id);
            $s->branch_name = $branch ? $branch->name : 'Unknown';
        }

        $this->view('students/index', [
            'students' => $students,
            'page' => $page,
            'totalPages' => $totalPages,
            'searchField' => $_GET['search_field'] ?? '',
            'searchValue' => $_GET['search_value'] ?? ''
        ]);
    }



    public function add()
    {
        // Load branch of logged-in user (receptionist)
        $branch_id = $_SESSION['user']['branch_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $fullname    = $_POST['fullname'] ?? '';
            $dob         = $_POST['dob'] ?? '';
            $email       = $_POST['email'] ?? '';
            $phone       = $_POST['phone'] ?? '';
            $category    = $_POST['category'] ?? '';
            $course_type = $_POST['course_type'] ?? '';
            $boarding    = $_POST['boarding'] ?? 'No';
            $start_date  = $_POST['start_date'] ?? ''; // <- add this
            // Calculate fees
            $fees = $this->calculateFees($category, $course_type, $boarding);


            // Insert student
            $this->studentModel->insert([
                'fullname'    => $fullname,
                'dob'         => $dob,
                'email'       => $email,
                'phone'       => $phone,
                'category'    => $category,
                'course_type' => $course_type,
                'boarding'    => strtolower($boarding) === 'yes' ? 'yes' : 'no',
                'fees'        => $fees,
                'branch_id'   => $branch_id,
                'start_date' => $start_date, // <- add this
                'paid'        => 0,
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            // Redirect to students list
            redirect('students');
        }

        // Show form
        $this->view('students/add');
    }

    /**
     * Calculate fees based on category, course type, and boarding
     */
    private function calculateFees($category, $course_type, $boarding)
    {
        $prices = [
            'C1' => [
                'Full'     => 703000,
                'Half'     => 650000,
                '30 days'  => 500000,
                '15 days'  => 0 // Assuming no 15 days for C1? adjust if needed
            ],
            'B'  => [
                'Full'     => 653000,
                'Half'     => 600000,
                '30 days'  => 500000,
                '15 days'  => 0
            ],
            'A1' => [
                'Full' => 303000,
                'Half' => 0,       // Not available
                '30 days' => 0,
                '15 days' => 0
            ],
        ];

        $fee = $prices[$category][$course_type] ?? 0;

        if (strtolower($boarding) === 'yes') {
            $fee += 30000; // Boarding fee
        }

        return $fee;
    }

    public function pay($id)
    {
        $student = $this->studentModel->findById($id);

        if (!$student) {
            $_SESSION['error'] = "Student not found.";
            redirect('students');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $amount = (float)$_POST['amount'];
            $payment_date = $_POST['payment_date'];

            $remaining = $student->fees - ($student->paid ?? 0);

            // Validation
            if ($amount <= 0 || $amount > $remaining) {
                $_SESSION['error'] = "Invalid payment amount.";
                redirect('students');
            }

            if (strtotime($payment_date) > time()) {
                $_SESSION['error'] = "Payment date cannot be in the future.";
                redirect('students');
            }

            // 📸 Receipt upload
            $receiptPath = null;

            if (!empty($_FILES['receipt']['name'])) {

                $allowed = ['image/jpeg', 'image/png'];
                if (!in_array($_FILES['receipt']['type'], $allowed)) {
                    $_SESSION['error'] = "Only JPG or PNG images allowed.";
                    redirect('students');
                }

                $uploadDir = "uploads/receipts/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid('receipt_') . '_' . $_FILES['receipt']['name'];
                $receiptPath = $uploadDir . $fileName;

                move_uploaded_file($_FILES['receipt']['tmp_name'], $receiptPath);
            }

            // Save payment
            $paymentModel = new Payment();
            $paymentModel->insert([
                'student_id'   => $id,
                'amount'       => $amount,
                'payment_date' => $payment_date,
                'receipt'      => $receiptPath,
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            // Update student paid
            $this->studentModel->update($id, [
                'paid' => ($student->paid ?? 0) + $amount
            ]);

            $_SESSION['success'] = "Payment recorded successfully!";
            redirect('payments');
        }
    }


    public function dashboard()
    {
        require_auth();

        $userRole = $_SESSION['user']['role'] ?? '';

        if ($userRole === 'receptionist') {
            // Get total students for this receptionist's branch
            $students = $this->studentModel->where('branch_id', $this->branch_id);

            $totalStudents = count($students);

            // Optionally, get total payments made for their students
            $totalPaid = 0;
            foreach ($students as $s) {
                $totalPaid += $s->paid ?? 0;
            }

            // Load receptionist dashboard view
            $this->view('students/dashboard', [
                'totalStudents' => $totalStudents,
                'totalPaid' => $totalPaid
            ]);
        } else {
            // For manager, redirect to manager dashboard
            redirect('manager/dashboard');
        }
    }
}
