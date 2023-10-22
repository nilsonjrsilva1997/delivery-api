<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\OrderStatus;

class OrderStatusController extends Controller
{
    public function index()
    {
        return OrderStatus::all();
    }

    public function show($id)
    {
        $orderStatus = OrderStatus::findOrFail($id);
        return $orderStatus;
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return OrderStatus::create($validatedData);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
        ]);

        $orderStatus = OrderStatus::findOrFail($id);
        $orderStatus->fill($validatedData);
        $orderStatus->save();
        return $orderStatus;
    }

    public function destroy($id)
    {
        $orderStatus = OrderStatus::findOrFail($id);
        $orderStatus->delete();
    }
}
