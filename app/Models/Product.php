<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'category_id', 'price', 'restaurant_id', 'image'];

    protected $table = 'products';

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => URL::to('/') . '/storage' . '/' . ($value)
        );
    }

    public function images() {
        return $this->hasMany(ProductImage::class);
    }

    public function restaurant() {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
