<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';

    protected $fillable = [
        'transaction',
        'd_id',
        'name',
        'date',
        'price',
        'payment'
    ];

    // You can define additional properties or methods here
}

