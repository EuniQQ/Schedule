<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donate extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'name',
        'account',
        'pay_on_line',
        'bank',
        'form_link',
        'tel'
    ];
}
