<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fiallble = ['address_id', 'total', 'payment_method_id', 'user_id'];

    protected $table = 'orders';

    // public function payment_method() {
    //     return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    // }

    public function address() {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }
}
