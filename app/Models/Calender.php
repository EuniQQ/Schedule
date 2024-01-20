<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calender extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'birthday_person',
        'mc_start',
        'mc_end',
        'plan',
        'plan_time',
        'tag_color',
        'tag_title',
        'tag_form',
        'tag_to',
        'sticker',
        'photos_link',
    ];
}
