<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public $timestamps = false;

    protected $table = 'expenses';

    protected $fillable = [
        'amount',
        'category',
        'note',
        'expense_date',
        'created_at',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];
}