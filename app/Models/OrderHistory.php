<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = ['order_status_id', 'order_id'];

    protected $table = 'order_histories';

    public function order_status() {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }

    public function order() {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }
}
