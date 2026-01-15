<?php
class Payment extends Model
{
    protected string $table = 'payments'; // your payments table
    protected array $columns = [
        'id',
        'student_id',
        'amount',
        'payment_date',
        'created_at',
        'receipt'
    ];

    public function paginate($limit, $offset, $search = '')
    {
        $sql = "
            SELECT 
                p.*,
                s.fullname,
                s.phone
            FROM payments p
            JOIN students s ON s.id = p.student_id
            WHERE 
                s.fullname LIKE :search OR
                s.phone LIKE :search OR
                p.payment_date LIKE :search
            ORDER BY p.created_at DESC
            LIMIT $limit OFFSET $offset
        ";

        return $this->query($sql, [
            'search' => "%$search%"
        ]);
    }

    public function count($search = '')
    {
        $sql = "
            SELECT COUNT(*) as total
            FROM payments p
            JOIN students s ON s.id = p.student_id
            WHERE 
                s.fullname LIKE :search OR
                s.phone LIKE :search OR
                p.payment_date LIKE :search
        ";

        $result = $this->query($sql, [
            'search' => "%$search%"
        ]);

        return $result[0]->total ?? 0;
    }
}
