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
        'status'     
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

    protected static function boot()
    {
        parent::boot();

        // Listen for the creating event and set the due_date attribute
        static::creating(function ($debtor) {
            // Use Carbon to add 15 days to the created_at date
            $dueDate = Carbon::parse($debtor->created_at)->addDays(15);

            // Assign the calculated due date to the due_date attribute
            $debtor->due_date = $dueDate;
        });
    }

    
}