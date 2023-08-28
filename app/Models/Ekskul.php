<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ekskul extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'table_ekskuls'; // Specify the correct table name here
    protected $fillable = [
        'image', 
        'title',
        'description',
    ];
}
