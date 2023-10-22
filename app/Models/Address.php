<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['road', 'district', 'city', 'zip_code', 'number', 'complement', 'user_id'];

    protected $table = 'addresses';

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
