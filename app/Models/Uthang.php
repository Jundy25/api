<?php

// app/Models/Uthang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uthang extends Model
{
    use HasFactory;

    protected $table = 'uthangs';

    protected $fillable = [
        'd_id',
        'item_id',
        'quantity',
        'added_on',
        'updated_at',
    ];

    public function debtor()
    {
        return $this->belongsTo(Debtor::class, 'd_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}

