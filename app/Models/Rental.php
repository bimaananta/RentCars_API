<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_date', 'return_date', 'price', 'user_id', 'car_id'
    ];

    protected $table = 'rentals';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cars()
    {
        return $this->belongsTo(Cars::class, 'car_id', 'id');
    }
}
