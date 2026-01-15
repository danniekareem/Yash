<?php
class Student extends Model
{
    protected string $table = 'students'; // your DB table

    /**
     * Get paginated students with optional search
     * @param int $limit
     * @param int $offset
     * @param array $filters ['field' => 'value']
     * @return array
     */
    protected array $columns = [
        'id',
        'fullname',
        'category',
        'dob',
        'email',
        'phone',
        'course_type',
        'branch_id',
        'fees',
        'paid',
        'boarding',
        'created_at'
    ];

    /**
     * Calculate fees based on category, course type, and boarding
     */
    public function calculateFees($category, $course_type, $boarding)
    {
        $fees = 0;

        $pricing = [
            'C1' => ['Full' => 703000, 'Half' => 650000, '30 days' => 500000, '15 days' => 500000],
            'B'  => ['Full' => 653000, 'Half' => 600000, '30 days' => 500000, '15 days' => 500000],
            'A1' => ['Full' => 303000]
        ];

        if (isset($pricing[$category][$course_type])) {
            $fees = $pricing[$category][$course_type];
        }

        if ($boarding === 'Yes') $fees += 30000;

        return $fees;
    }

    public function getPaginated($limit, $offset, $filters = [])
    {
        $sql = "SELECT s.*, b.name AS branch_name, 
                   COALESCE(p.total_paid, 0) AS paid
            FROM students s
            LEFT JOIN branches b ON s.branch_id = b.id
            LEFT JOIN (
                SELECT student_id, SUM(amount) AS total_paid
                FROM payments
                GROUP BY student_id
            ) p ON s.id = p.student_id
            WHERE 1=1";

        $params = [];

        // Apply filters
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if ($field === 'fullname' || $field === 'category' || $field === 'phone') {
                    $sql .= " AND s.$field LIKE ?";
                    $params[] = "%$value%";
                } elseif ($field === 'start_date') {
                    $sql .= " AND s.start_date = ?";
                    $params[] = $value;
                }
            }
        }

        // Inject limit & offset directly (safe because they are integers)
        $sql .= " ORDER BY s.id DESC LIMIT $limit OFFSET $offset";

        return $this->query($sql, $params);
    }


    /**
     * Count students with optional filters
     */
    public function countFiltered($filters = [])
    {
        $sql = "SELECT COUNT(*) AS total FROM students s WHERE 1=1";
        $params = [];

        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if ($field === 'fullname' || $field === 'category' || $field === 'phone') {
                    $sql .= " AND s.$field LIKE ?";
                    $params[] = "%$value%";
                } elseif ($field === 'start_date') {
                    $sql .= " AND s.start_date = ?";
                    $params[] = $value;
                }
            }
        }

        $result = $this->query($sql, $params);
        return $result ? $result[0]->total : 0;
    }
}
