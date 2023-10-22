<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where(['user_id' => Auth::id()])->get();
        return $addresses;
    }

    public function show($id)
    {
        $address = Address::where(['id' => $id, 'user_id' => Auth::id()])->first();

        if(empty($address)) {
            return response()->json(['messsage' => 'Endereço não encontrado'], 404);
        }

        return $address;
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'road' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|formato_cep',
            'number' => 'required|string|max:255',
            'complement' => 'string|max:255'
        ]);

        $validatedData['user_id'] = Auth::id();

        $address = Address::create($validatedData);

        return $address;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'road' => 'string|max:255',
            'district' => 'string|max:255',
            'city' => 'string|max:255',
            'zip_code' => 'string|max:255',
            'number' => 'string|max:255',
            'complement' => 'string|max:255'
        ]);

        $address = Address::findOrFail($id);
        $address->fill($validatedData);
        $address->save();
        return $address;
    }

    public function destroy($id)
    {
        $address = Address::where(['id' => $id, 'user_id' => Auth::id()])->first();

        if(empty($address)) {
            return response()->json(['message' => 'Endereço não encontrado'], 404);
        }

        $address->delete();
    }
}
