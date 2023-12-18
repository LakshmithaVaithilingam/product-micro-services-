<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    //update issue 
    protected $primaryKey = 'products_id';

    //new added
    protected $casts = [
        'images' => 'array',
    ];

    protected $fillable = ['name', 'description', 'quantity', 'size', 'color', 'price','images', 'category_id', 'subcategory_id', 'age_group', 'discount'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
