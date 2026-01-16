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

        $stats = [
            'total_students'   => $studentModel->countAll(),
            'active_students'  => $studentModel->countWhere(['status' => 'active']),
            'total_fees'       => $studentModel->sum('fees'),
            'total_paid'       => $studentModel->sum('paid'),
        ];

        $branch_id = $_SESSION['user']['branch_id'];

        $recentStudents = $studentModel->findAllWithBranch(5);


        $stats['outstanding'] = $stats['total_fees'] - $stats['total_paid'];

        //$recentStudents = $studentModel->limit(5)->findAll();

        $this->view('manager/dashboard', [
            'stats' => $stats,
            'students' => $recentStudents
        ]);
    }
}
