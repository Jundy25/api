<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uthang extends Model
{
    use HasFactory;

    protected $table = 'uthangs';
    protected $primaryKey = 'u_id';
    public $timestamps = false;

    protected $fillable = [
        'd_id',
        'item_id',
        'quantity',
        'price',
        'total',
        'added_on',
        'updated_at',
        // Add other fillable columns if needed
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    
}