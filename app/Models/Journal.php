<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'title',
        'content',
        'photos_link',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function journal_photos(): HasMany
    {
        return $this->hasMany(Journal_photo::class);
    }

}
