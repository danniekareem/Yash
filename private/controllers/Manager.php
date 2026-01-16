<?php

class Manager extends Controller
{
    public function index()
    {
        require_auth();

        if ($_SESSION['user']['role'] !== 'management') {
            redirect('students');
        }

        $studentModel = $this->load_model('Student');
        $branchModel  = $this->load_model('Branch');

        // Optional branch filter from GET
        $branch_id = $_GET['branch_id'] ?? null;

        // Stats for selected branch or overall
        if ($branch_id) {
            $stats = [
                'total_students'   => $studentModel->countWhere(['branch_id' => $branch_id]),
                'active_students'  => $studentModel->countWhere(['branch_id' => $branch_id, 'status' => 'active']),
                'total_fees'       => $studentModel->sumColumnByBranch('fees', $branch_id),
                'total_paid'       => $studentModel->sumColumnByBranch('paid', $branch_id),
            ];
        } else {
            $stats = [
                'total_students'   => $studentModel->countAll(),
                'active_students'  => $studentModel->countWhere(['status' => 'active']),
                'total_fees'       => $studentModel->sum('fees'),
                'total_paid'       => $studentModel->sum('paid'),
            ];
        }

        $stats['outstanding'] = $stats['total_fees'] - $stats['total_paid'];

        // Get recent students with branch info
        $recentStudents = $studentModel->getStudentsByBranch($branch_id, 5);

        // Branch performance summary for dashboard
        $branchPerformance = $studentModel->branchPerformance();

        $branches = $branchModel->findAll();

        $this->view('manager/dashboard', [
            'stats' => $stats,
            'students' => $recentStudents,
            'branches' => $branches,
            'selected_branch' => $branch_id,
            'branchPerformance' => $branchPerformance
        ]);
    }
}
