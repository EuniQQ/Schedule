<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'item',
        'shop',
        'amount',
        'actual_pay',
        'bank',
        'notes'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
