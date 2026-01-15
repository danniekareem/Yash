<?php
class Payments extends Controller
{
    private $paymentModel;

    public function __construct()
    {
        $this->paymentModel = new Payment();
    }

    public function index()
    {
        require_auth();

        $studentModel = $this->load_model('Student');
        $paymentModel = $this->load_model('Payment');

        $limit = 10;
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $limit;
        $search = $_GET['search'] ?? '';

        $students = $studentModel->getStudentsWithPayments($limit, $offset, $search);
        $total = $studentModel->countStudentsWithPayments($search);
        $totalPages = ceil($total / $limit);

        // Attach payment history per student
        foreach ($students as $s) {
            $s->payments = $paymentModel->where('student_id', $s->id);
            $s->balance = $s->fees - ($s->paid ?? 0);
        }

        $this->view('payments/index', [
            'students' => $students,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ]);
    }
}
