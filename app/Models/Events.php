<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;
    protected $table = 'table_events'; // Specify the correct table name here
    protected $fillable = [
        'image', 
        'title',
        'description',
    ];
}
