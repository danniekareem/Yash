<?php
class User extends Model
{
    protected string $table = 'users';
    protected array $columns = ['id', 'fullname', 'email', 'password', 'role', 'branch_id', 'status'];

    public function findByEmail($email)
    {
        $rows = $this->where('email', $email);
        return $rows[0] ?? false;
    }

    public function getAllWithBranch()
    {
        $sql = "
            SELECT u.*, b.name AS branch_name
            FROM users u
            LEFT JOIN branches b ON b.id = u.branch_id
            
        ";
        return $this->query($sql);
    }
}
