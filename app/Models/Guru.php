<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'table_gurus'; // Specify the correct table name here
    protected $fillable = [
        'image', 
        'title',
    ];
}
