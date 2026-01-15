<?php

class Auth extends Controller
{
    public function index()
    {
        $this->view('auth/login');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('auth');
        }

        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            $_SESSION['error'] = 'Invalid email or password';
            redirect('auth');
        }

        if ($user->status !== 'active') {
            $_SESSION['error'] = 'Account inactive';
            redirect('auth');
        }

        // Load user and branch
        $userModel   = $this->load_model('User');
        $branchModel = $this->load_model('Branch');

        $user = $userModel->findByEmail($email); // or findById if you have user id
        $branch = $branchModel->findById($user->branch_id);

        // Save to session
        $_SESSION['user'] = [
            'id'          => $user->id,
            'name'        => $user->fullname, // display name
            'email'       => $user->email,
            'role'        => $user->role,
            'branch_id'   => $user->branch_id,
            'branch_name' => $branch ? $branch->name : 'Unknown'
        ];


        if ($user->role === 'management') {
            redirect('manager/dashboard');
        } else {
            redirect('students/dashboard');
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        redirect('auth');
    }
}
