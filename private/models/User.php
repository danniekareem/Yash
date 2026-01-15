<?php
class User extends Model
{
    protected array $columns = ['id', 'fullname', 'email', 'password', 'role', 'branch_id', 'status'];

    public function findByEmail($email)
    {
        $rows = $this->where('email', $email);
        return $rows[0] ?? false;
    }
}
