<?php

class Branches extends Controller
{
    private $branchModel;

    public function __construct()
    {
        require_auth();

        if ($_SESSION['user']['role'] !== 'management') {
            redirect('students/dashboard');
        }

        $this->branchModel = $this->load_model('Branch');
    }

    // 📄 List branches
    public function index()
    {
        $branches = $this->branchModel->findAll();

        $this->view('branches/index', [
            'branches' => $branches
        ]);
    }

    // ➕ Add branch
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->branchModel->insert([
                'name' => $_POST['name'],
                'status' => 'active'
            ]);

            redirect('branches');
        }

        $this->view('branches/add');
    }

    // ✏️ Edit branch
    public function edit($id)
    {
        $branch = $this->branchModel->findById($id);
        if (!$branch) redirect('branches');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->branchModel->update($id, [
                'name'   => $_POST['name'],
                'status' => $_POST['status']
            ]);

            redirect('branches');
        }

        $this->view('branches/edit', ['branch' => $branch]);
    }

    // 🔄 Soft delete (activate / deactivate)
    public function toggle($id)
    {
        $branch = $this->branchModel->findById($id);
        if (!$branch) redirect('branches');

        $newStatus = $branch->status === 'active' ? 'inactive' : 'active';

        $this->branchModel->update($id, [
            'status' => $newStatus
        ]);

        redirect('branches');
    }
}
