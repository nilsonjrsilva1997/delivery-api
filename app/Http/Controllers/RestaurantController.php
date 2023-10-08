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

        if(empty($restaurant)) {
            return response()->json(['message' => 'Nenhum restaurante cadastrado'], 404);
        }

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
            'logo' => 'required|mimes:jpg,png,webp|max:2048',
            'banner' => 'required|mimes:jpg,png,webp|max:2048'
        ]);

        $validatedData['user_id'] = Auth::id();

        $countRestaurant = Restaurant::where(['user_id' => $validatedData['user_id']])->first();

        if(!empty($countRestaurant)) {
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

        $restaurant = Restaurant::findOrFail($id);

        if(Auth::id() != $restaurant['user_id']) {
            return response()->json(['message' => 'Não autorizado'], 401);
        }

        if(!empty($request->file('logo'))) {
            Storage::disk('public')->delete('/' . $restaurant['logo']);
            $validatedData['logo'] = Storage::disk('public')->put('/', $request->file('logo'));
        }

        if(!empty($request->file('banner'))) {
            Storage::disk('public')->delete('/' . $restaurant['banner']);
            $validatedData['banner'] = Storage::disk('public')->put('/', $request->file('banner'));
        }

        $restaurant->fill($validatedData);
        $restaurant->save();
        return $restaurant;
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        if(Auth::id() != $restaurant['user_id']) {
            return response()->json(['message' => 'Não autorizado'], 401);
        }

        Storage::disk('public')->delete('/' . $restaurant['banner']);
        Storage::disk('public')->delete('/' . $restaurant['logo']);

        $restaurant->delete();
    }
}
