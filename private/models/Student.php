<?php
class Student extends Model
{
    protected string $table = 'students'; // your DB table
    protected $limit;
    protected $offset;


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
        'created_at',
        'start_date',
        'status'

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
                } elseif ($field === 'branch_id') { // 🔹 ADD THIS
                    $sql .= " AND s.branch_id = ?";
                    $params[] = $value;
                }
            }
        }

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
                } elseif ($field === 'branch_id') { // 🔹 ADD THIS
                    $sql .= " AND s.branch_id = ?";
                    $params[] = $value;
                }
            }
        }

        $result = $this->query($sql, $params);
        return $result ? $result[0]->total : 0;
    }
    /**
     * Get students along with total payments, with optional search and branch restriction
     */
    /**
     * Get paginated students with optional search and branch restriction
     */
    public function getPaginatedWithPayments($limit, $offset, $search = '', $branch_id = null)
    {
        $sql = "
            SELECT s.*, 
                   IFNULL(SUM(p.amount),0) AS paid,
                   b.name AS branch_name
            FROM students s
            LEFT JOIN payments p ON p.student_id = s.id
            LEFT JOIN branches b ON s.branch_id = b.id
            WHERE (s.fullname LIKE :search OR s.phone LIKE :search)
        ";

        $params = ['search' => "%$search%"];

        if ($branch_id !== null) {
            $sql .= " AND s.branch_id = :branch_id";
            $params['branch_id'] = $branch_id;
        }

        $sql .= "
            GROUP BY s.id
            ORDER BY s.created_at DESC
            LIMIT $limit OFFSET $offset
        ";

        return $this->query($sql, $params);
    }

    /**
     * Count students with payments, optional search and branch restriction
     */
    public function countWithPayments($search = '', $branch_id = null)
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM students s
            WHERE (s.fullname LIKE :search OR s.phone LIKE :search)
        ";

        $params = ['search' => "%$search%"];

        if ($branch_id !== null) {
            $sql .= " AND s.branch_id = :branch_id";
            $params['branch_id'] = $branch_id;
        }

        $result = $this->query($sql, $params);
        return $result[0]->total ?? 0;
    }

    /**
     * Count all students (optional branch restriction)
     */
    public function countAll($branch_id = null)
    {
        $sql = "SELECT COUNT(*) AS total FROM students";
        $params = [];

        if ($branch_id !== null) {
            $sql .= " WHERE branch_id = :branch_id";
            $params['branch_id'] = $branch_id;
        }

        $result = $this->query($sql, $params);
        return $result[0]->total ?? 0;
    }


    /**
     * Get students for a specific branch or all
     */
    public function getByBranch($branch_id = null)
    {
        $sql = "SELECT * FROM students";
        $params = [];

        if ($branch_id !== null) {
            $sql .= " WHERE branch_id = :branch_id";
            $params['branch_id'] = $branch_id;
        }

        return $this->query($sql, $params);
    }

    /**
     * Sum a column (optional branch restriction)
     */
    public function sumColumn($column, $branch_id = null)
    {
        $sql = "SELECT SUM($column) AS total FROM students";
        $params = [];

        if ($branch_id !== null) {
            $sql .= " WHERE branch_id = :branch_id";
            $params['branch_id'] = $branch_id;
        }

        $result = $this->query($sql, $params);
        return $result[0]->total ?? 0;
    }


    // Count rows matching conditions
    public function countWhere(array $where = [])
    {
        if (empty($where)) {
            return $this->countAll();
        }

        $keys = array_keys($where);
        $conditions = implode(' AND ', array_map(fn($k) => "$k = :$k", $keys));
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE $conditions";
        $result = $this->query($query, $where);

        return $result[0]->total ?? 0;
    }


    // Sum a column
    public function sum(string $column)
    {
        $query = "SELECT SUM($column) as total FROM {$this->table}";
        $result = $this->query($query);
        return $result[0]->total ?? 0;
    }


    public function limit(int $limit, int $offset = 0)
    {
        $this->limit  = $limit;
        $this->offset = $offset;
        return $this; // for chaining
    }

    public function findAllWithBranch($limit = null, $offset = 0)
    {
        $sql = "SELECT s.*, b.name AS branch_name
            FROM {$this->table} s
            LEFT JOIN branches b ON s.branch_id = b.id";

        if ($limit !== null) {
            $sql .= " LIMIT {$offset}, {$limit}";
        }

        return $this->query($sql);
    }
}
