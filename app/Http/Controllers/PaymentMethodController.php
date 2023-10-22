<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return PaymentMethod::all();
    }

    public function show($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return $paymentMethod;
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return PaymentMethod::create($validatedData);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->fill($validatedData);
        $paymentMethod->save();
        return $paymentMethod;
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();
    }
}
