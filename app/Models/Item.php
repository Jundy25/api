<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'item_name',
        'price',
        'category',
    ];

    public function uthangs()
    {
        return $this->hasMany(Uthang::class, 'item_id');
    }
}