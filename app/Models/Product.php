<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $casts = [
        'description' => 'json',
        'meta_data' => 'json',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }
}
