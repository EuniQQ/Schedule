<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Style extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'user_id',
        'main_img',
        'header_img',
        'footer_img',
        'footer_color',
        'bg_color',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
