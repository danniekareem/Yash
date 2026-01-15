<?php

class Manager extends Controller
{
    public function __construct()
    {
        // Require user to be logged in
        require_auth();

        // Only managers can access
        if ($_SESSION['user']['role'] !== 'management') {
            redirect('auth');
        }
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        // Load models
        $studentModel = $this->load_model('Student');
        $branchModel  = $this->load_model('Branch');
        $paymentModel = $this->load_model('Payment'); // Make sure Payment model exists

        // Get all students
        $students = $studentModel->findAll();

        // Enhance students with branch name and total paid
        foreach ($students as &$s) {
            $branch = $branchModel->findById($s->branch_id);
            $s->branch_name = $branch ? $branch->name : 'Unknown';

            // Sum all payments for this student
            $payments = $paymentModel->where('student_id', $s->id); // returns array of payments
            $s->paid = 0;
            if ($payments) {
                foreach ($payments as $p) {
                    $s->paid += $p->amount;
                }
            }
        }

        // Branch summary
        $branches = $branchModel->findAll();
        foreach ($branches as &$b) {
            $count = $studentModel->where('branch_id', $b->id);
            $b->total_students = $count ? count($count) : 0;
        }

        // Category summary
        $categories = ['A1', 'B', 'C1'];
        $categoryData = [];
        foreach ($categories as $cat) {
            $count = $studentModel->where('category', $cat);
            $categoryData[] = [
                'category' => $cat,
                'count'    => $count ? count($count) : 0
            ];
        }

        // Payment totals
        $totalPaid    = array_sum(array_column($students, 'paid'));
        $totalPending = array_sum(array_map(fn($s) => max(0, $s->fees - $s->paid), $students));

        // Render view
        $this->view('manager/dashboard', [
            'students'     => $students,
            'branches'     => $branches,
            'categories'   => $categoryData,
            'totalPaid'    => $totalPaid,
            'totalPending' => $totalPending
        ]);
    }
}
