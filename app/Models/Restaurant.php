<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo', 'banner', 'user_id'];

    protected $table = 'restaurants';

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}