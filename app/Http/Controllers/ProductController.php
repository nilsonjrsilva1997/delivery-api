<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $restaurant = User::where(['id' => Auth::id()])->with('restaurants')->first();

        if(empty($restaurant['restaurants'])) {
            return response()->json(['message' => 'Cadastre um restaurante na plataforma'], 422);
        }

        $products = Product::where(['restaurant_id' => $restaurant['restaurants']['id']])->get();

        return $products;
    }

    public function show($id)
    {
        $restaurant = User::where(['id' => Auth::id()])->with('restaurants')->first();

        if(empty($restaurant['restaurants'])) {
            return response()->json(['message' => 'Cadastre um restaurante na plataforma'], 422);
        }

        $product = Product::where(['restaurant_id' => $restaurant['restaurants']['id'], 'id' => $id])->first();

        if(empty($product)) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
        return $product;
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|mimes:jpg,png,webp|max:2048',
            'description' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'price' => 'required|numeric',
        ]);

        $restaurant = User::where(['id' => Auth::id()])->with('restaurants')->first();

        if(empty($restaurant['restaurants'])) {
            return response()->json(['message' => 'Cadastre um restaurante na plataforma'], 422);
        }

        $validatedData['restaurant_id'] = $restaurant['restaurants']['id'];

        $validatedData['image'] = Storage::disk('public')->put('/', $request->file('image'));

        $product = Product::create($validatedData);

        $product['image'] = url('/storage') . '/' . $product['image'];

        return $product;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'image' => 'mimes:jpg,png,webp|max:2048',
            'description' => 'string|max:255',
            'category_id' => 'integer|exists:categories,id',
            'price' => 'numeric',
        ]);

        $product = Product::findOrFail($id);

        $restaurant = User::where(['id' => Auth::id()])->with('restaurants')->first();

        if(empty($restaurant['restaurants'])) {
            return response()->json(['message' => 'Cadastre um restaurante na plataforma'], 422);
        }

        if($product['restaurant_id'] != $restaurant['id']) {
            return response()->json(['message' => 'Não autorizado'], 401);
        }

        if(!empty($request->file('image'))) {
            Storage::disk('public')->delete('/' . $product['image']);
            $validatedData['image'] = Storage::disk('public')->put('/', $request->file('image'));
        }

        $product->fill($validatedData);
        $product->save();

        return $product;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $restaurant = User::where(['id' => Auth::id()])->with('restaurants')->first();

        if(empty($restaurant['restaurants'])) {
            return response()->json(['message' => 'Cadastre um restaurante na plataforma'], 422);
        }

        if($product['restaurant_id'] != $restaurant['id']) {
            return response()->json(['message' => 'Não autorizado'], 401);
        }

        Storage::disk('public')->delete('/' . $product['image']);

        $product->delete();
    }
}
