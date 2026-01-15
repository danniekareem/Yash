<?php

class Branch extends Model
{
    protected string $table = 'branches'; // DB table name
    protected array $columns = [
        'id',
        'name',
        'location',
        'created_at'
    ];
}
