<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journal_photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_id',
        'url',
        'dsecription',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function journal_photo(): BelongsTo
    {
        return $this->belongsTo(Journal_photo::class);
    }

}
