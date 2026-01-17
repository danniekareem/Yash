<?php

class Users extends Controller
{
    private $userModel;
    private $branchModel;

    public function __construct()
    {
        require_auth();

        if ($_SESSION['user']['role'] !== 'management') {
            redirect('students');
        }

        $this->userModel = $this->load_model('User');
        $this->branchModel = $this->load_model('Branch');
    }

    public function index()
    {
        $users = $this->userModel->getAllWithBranch();


        $this->view('users/index', [
            'users' => $users
        ]);
    }

    // Show create form + handle submit
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'fullname'  => trim($_POST['fullname']),
                'email'     => trim($_POST['email']),
                'password'  => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role'      => $_POST['role'],
                'branch_id' => $_POST['role'] === 'receptionist' ? $_POST['branch_id'] : null,
                'status'    => $_POST['status'] ?? 'active'
            ];

            $this->userModel->insert($data);

            $_SESSION['success'] = "User created successfully";
            redirect('users');
        }

        // Load branches for dropdown
        $branches = $this->branchModel->findAll();

        $this->view('users/create', [
            'branches' => $branches
        ]);
    }

    public function edit($id)
    {
        require_auth();

        // Only manager can edit users
        if ($_SESSION['user']['role'] !== 'management') {
            redirect('students');
        }

        $userModel   = $this->load_model('User');
        $branchModel = $this->load_model('Branch');

        $user = $userModel->findById($id);
        if (!$user) {
            redirect('users');
        }

        $branches = $branchModel->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'role'   => $_POST['role'],
                'status' => $_POST['status'],
                'branch_id' => $_POST['role'] === 'management'
                    ? null
                    : ($_POST['branch_id'] ?? null)
            ];

            $userModel->update($id, $data);

            $_SESSION['success'] = "User updated successfully";
            redirect('users');
        }

        $this->view('users/edit', [
            'user' => $user,
            'branches' => $branches
        ]);
    }

    public function toggleStatus($id)
    {
        require_auth();

        if ($_SESSION['user']['role'] !== 'management') {
            redirect('users');
        }

        $userModel = $this->load_model('User');
        $user = $userModel->findById($id);
        if (!$user) redirect('users');

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';

        $userModel->update($id, ['status' => $newStatus]);

        redirect('users');
    }
}
