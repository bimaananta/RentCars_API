<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'title', 'description', 'link', 'photo1', 'photo2', 'brand', 'model', 'fuel_type', 'price', 'gearbox', 'available', 'status'
    ];

    public function rental()
    {
        return $this->hasMany(Rental::class);
    }

}
