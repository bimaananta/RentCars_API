<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'links', 'id_category', 'id_user', 'create_date', 'status'
    ];

    protected $primaryKey = 'id_pages';

    protected $table = 'pages';
}
