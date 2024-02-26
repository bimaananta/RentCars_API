<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'id_category', 'links', 'id_user', 'create_date', 'status'
    ];

    protected $primaryKey = 'id_news';

    protected $table = 'news';

    public function category(){
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }
}
