<?php
class Payments extends Controller
{
    private $paymentModel;
    private $studentModel;

    public function __construct()
    {
        require_auth();

        // Load models
        $this->paymentModel = $this->load_model('Payment');
        $this->studentModel = $this->load_model('Student');
    }

    public function index()
    {
        require_auth();

        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $search = $_GET['search'] ?? '';

        // Filters for receptionist branch
        $filters = [];
        if ($_SESSION['user']['role'] === 'receptionist') {
            $filters['branch_id'] = $_SESSION['user']['branch_id'];
        }

        // Fetch students with payments and optional search
        $students = $this->studentModel->getPaginated($limit, $offset, $filters);

        // Apply search filter manually if needed
        if (!empty($search)) {
            $students = array_filter($students, function ($s) use ($search) {
                return str_contains(strtolower($s->fullname), strtolower($search)) ||
                    str_contains(strtolower($s->phone), strtolower($search));
            });
        }

        // Count total for pagination
        $totalStudents = $this->studentModel->countFiltered($filters);
        $totalPages = ceil($totalStudents / $limit);

        // Attach payments and balance for each student
        foreach ($students as &$s) {
            $s->payments = $this->paymentModel->where('student_id', $s->id) ?: [];
            $s->paid = $s->paid ?? 0; // Ensure paid is defined
            $s->fees = $s->fees ?? 0; // Ensure fees is defined
            $s->balance = $s->fees - $s->paid;
        }

        // Load the payments view
        $this->view('payments/index', [
            'students'   => $students,
            'page'       => $page,
            'totalPages' => $totalPages,
            'search'     => $search
        ]);
    }
}
