<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Galeries extends Model
{
    use HasFactory;
    protected $table = 'galeries'; // Specify the correct table name here
    protected $fillable = [
        'image', 
    ];
}
