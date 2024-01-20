<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal_photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_id',
        'url',
        'dsecription',
    ];
}
