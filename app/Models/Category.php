<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';

    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}