<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableCategory extends Model
{
    protected $table = 'table_categories'; // Nama tabel di database

    protected $fillable = [
        'name', // Kolom yang bisa diisi
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at', // Kolom yang tidak akan ditampilkan dalam hasil JSON
    ];

   
}
