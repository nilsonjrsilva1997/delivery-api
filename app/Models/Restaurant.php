<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo', 'banner', 'user_id', 'slug'];

    protected $table = 'restaurants';

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => URL::to('/') . '/storage' . '/' . ($value)
        );
    }

    protected function banner(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => URL::to('/') . '/storage' . '/' . ($value)
        );
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function addresses() {
        return $this->hasMany(Restaurant::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function contact() {
        return $this->hasOne(Contact::class);
    }
}