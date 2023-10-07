<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where(['user_id' => Auth::id()])->first();
        
        if(empty($restaurant)) {
            return response()->json(['message' => 'Cadastre um restaurante para poder adicionar categorias'], 422);
        }

        $categories = Category::where(['restaurant_id' => $restaurant['id']])->get();

        return $categories;
    }

    public function show($id)
    {
        $restaurant = Restaurant::where(['user_id' => Auth::id()])->first();
        
        if(empty($restaurant)) {
            return response()->json(['message' => 'Cadastre um restaurante para poder adicionar categorias'], 422);
        }

        $category = Category::where(['restaurant_id' => $restaurant['id'], 'id' => $id])->first();

        if(empty($category)) {
            return response(['message' => 'Categoria não encontrada'], 404);
        }

        return response($category, 200);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $restaurant = Restaurant::where(['user_id' => Auth::id()])->first();

        if(empty($restaurant)) {
            return response()->json(['message' => 'Cadastre um restaurante para poder adicionar categorias'], 422);
        }

        $validatedData['restaurant_id'] = $restaurant['id'];

        $category = Category::create($validatedData);

        return $category;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->fill($validatedData);
        $category->save();
        return $category;
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }
}
