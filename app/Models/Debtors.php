<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debtors extends Model
{
    use HasFactory;

    protected $table = 'debtors';
    protected $primaryKey = 'd_id';

    protected $fillable = [
        'd_name', 
        'phone', 
        'address',       
    ];
    // You can also add other attributes as needed

    
}