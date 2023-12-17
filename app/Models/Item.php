<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $primaryKey = 'item_id';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}