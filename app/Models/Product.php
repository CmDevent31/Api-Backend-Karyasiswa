<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use SoftDeletes ;
    use HasFactory ;
    
    protected $table = 'table_products';
   // Product.php (or your Product model file)
protected $fillable = [
    'name', 'categori_id', 'description', 'price', 'discount', 'rating', 'brand', 'member_id', 'image',
];
// Product.php (or your Product model file)
protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];


    public function ProductStock()
    {
        return $this->belongsTo(ProductStock::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
}