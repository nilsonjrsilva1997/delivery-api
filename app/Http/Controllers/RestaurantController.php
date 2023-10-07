<?php

namespace App\Http\Controllers;

use App\Helpers\UploadFile;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where(['user_id' => Auth::id()])->first();

        $restaurant['logo'] = url('/storage') . '/' . $restaurant['logo'];
        $restaurant['banner'] = url('/storage') . '/' . $restaurant['banner'];

        return $restaurant;
    }

    public function show($id)
    {
        $restaurant = Restaurant::where(['user_id' => Auth::id(), 'id' => $id])->first();

        if(empty($restaurant)) {
            return response(['message' => 'Restaurante não encontrado'], 404);
        }

        return response($restaurant, 200);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|mimes:jpg,png|max:2048',
            'banner' => 'required|mimes:jpg,png|max:2048'
        ]);

        $validatedData['user_id'] = Auth::id();

        $countRestaurant = Restaurant::where(['user_id' => $validatedData['user_id']])->count();

        if($countRestaurant > 1) {
            return response()->json(['message' => 'Você já possui um restaurante cadastrado'], 422);
        }

        $validatedData['logo'] = Storage::disk('public')->put('/', $request->file('logo'));
        $validatedData['banner'] = Storage::disk('public')->put('/', $request->file('banner'));

        $restaurant = Restaurant::create($validatedData);

        $restaurant['logo'] = url('/storage') . '/' . $restaurant['logo'];
        $restaurant['banner'] = url('/storage') . '/' . $restaurant['banner'];

        return $restaurant;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
        ]);

        $menu = Restaurant::findOrFail($id);
        $menu->fill($validatedData);
        $menu->save();
        return $menu;
    }

    public function destroy($id)
    {
        $menu = Restaurant::findOrFail($id);
        $menu->delete();
    }
}
