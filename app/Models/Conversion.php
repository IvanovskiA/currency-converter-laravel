<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $fillable = [
        'from_currency',
        'to_currency',
        'amount',
        'rate',
        'result'
    ];
}
