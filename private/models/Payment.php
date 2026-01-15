<?php
class Payment extends Model
{
    protected string $table = 'payments'; // your payments table
    protected array $columns = [
        'id',
        'student_id',
        'amount',
        'payment_date',
        'created_at'
    ];
}
