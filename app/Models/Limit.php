<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Limit extends Model
{
    use HasFactory;

    protected $table = 'limit';
    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'amount',
        'updated_at',
    ];
}
