<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStock extends Model
{
    
    use HasFactory;

    protected $table = 'table_product_stocks';
    protected $fillable = [
        'id',
        'product_id',
        'qty'
        
        
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
