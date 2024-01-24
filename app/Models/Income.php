<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'content',
        'payer',
        'amount',
        'bank',
        'tithe',
        'tithe_date',
        'tithe_obj',
        'note'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
