<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Debtors extends Model
{

    use HasFactory;

    protected $table = 'debtors';
    protected $primaryKey = 'd_id';


    protected $fillable = [
        'd_name', 
        'email',
        'phone', 
        'address',
        'data_amount',
        'last_payment_date',
        'role_id',
        'due_date',
        'status',
        'img_path'    
    ];
    // You can also add other attributes as needed
    public function uthangs()
{
    return $this->hasMany(Uthang::class, 'd_id', 'd_id');
}
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    public function history()
{
    return $this->hasMany(History::class, 'd_id', 'd_id');
}




    
}