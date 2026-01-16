<?php
class Payment extends Model
{
    protected string $table = 'payments';
    protected array $columns = [
        'id',
        'student_id',
        'amount',
        'payment_date',
        'receipt',
        'created_at'
    ];

    /**
     * Get paginated payments with student info
     * Optionally restrict to a branch
     */
    public function paginate($limit, $offset, $search = '', $branch_id = null)
    {
        $sql = "
            SELECT 
                p.*,
                s.fullname,
                s.phone,
                s.branch_id,
                s.fees,
                s.paid
            FROM payments p
            JOIN students s ON s.id = p.student_id
            WHERE (s.fullname LIKE :search OR s.phone LIKE :search OR p.payment_date LIKE :search)
        ";

        $params = ['search' => "%$search%"];

        // Filter by branch if provided
        if ($branch_id !== null) {
            $sql .= " AND s.branch_id = :branch_id";
            $params['branch_id'] = $branch_id;
        }

        $sql .= " ORDER BY p.created_at DESC LIMIT $limit OFFSET $offset";

        return $this->query($sql, $params);
    }

    /**
     * Count payments with optional search and branch filter
     */
    public function count($search = '', $branch_id = null)
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM payments p
            JOIN students s ON s.id = p.student_id
            WHERE (s.fullname LIKE :search OR s.phone LIKE :search OR p.payment_date LIKE :search)
        ";

        $params = ['search' => "%$search%"];

        if ($branch_id !== null) {
            $sql .= " AND s.branch_id = :branch_id";
            $params['branch_id'] = $branch_id;
        }

        $result = $this->query($sql, $params);

        return $result[0]->total ?? 0;
    }
}
