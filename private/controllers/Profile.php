<?php

class Profile extends Controller
{
    public function index()
    {
        require_auth();

        $userModel = $this->load_model('User');
        $user = $userModel->findById($_SESSION['user']['id']);

        $this->view('profile/index', [
            'user' => $user
        ]);
    }

    public function changePassword()
    {
        require_auth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('profile');
        }

        $oldPassword     = $_POST['old_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'New passwords do not match';
            redirect('profile');
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            redirect('profile');
        }

        $userModel = $this->load_model('User');
        $user = $userModel->findById($_SESSION['user']['id']);

        // 🔐 Verify OLD password
        if (!password_verify($oldPassword, $user->password)) {
            $_SESSION['error'] = 'Old password is incorrect';
            redirect('profile');
        }

        // 🔒 Update password
        $userModel->update($user->id, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        $_SESSION['success'] = 'Password updated successfully';
        redirect('profile');
    }
}
