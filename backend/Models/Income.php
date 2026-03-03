<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    public $timestamps = false; // vì table không có updated_at

    protected $table = 'incomes';

    protected $fillable = [
        'amount',
        'category',
        'note',
        'income_date',
        'is_recurring',
        'recurring_type',
        'recurring_day',
        'created_at',
    ];

    protected $casts = [
        'income_date' => 'date',
        'is_recurring' => 'boolean',
    ];
}