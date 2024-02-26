<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'create_date', 'status'
    ];

    protected $primaryKey = 'id_category';
    protected $table = 'category';

    public function news(){
        return $this->hasMany(News::class);
    }
}
