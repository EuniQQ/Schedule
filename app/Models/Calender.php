<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Calender extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'birthday_person',
        'mc',
        'plan',
        'plan_time',
        'tag_color',
        'tag_title',
        'tag_form',
        'tag_to',
        'sticker',
        'photos_link',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
