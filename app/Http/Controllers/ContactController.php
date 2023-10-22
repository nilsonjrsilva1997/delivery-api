<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        return Contact::all();
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return $contact;
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'whatsapp' => 'required|string|max:255|celular_com_ddd',
            'email' => 'required|string|max:255|email',
            'facebook' => 'required|string|max:255|url',
            'instagram' => 'required|string|max:255|url',
            'youtube' => 'string|max:255|url',
            'linkedin' => 'string|max:255|url',
            'twiter' => 'string|max:255|url',
        ]);

        $restaurant = Restaurant::select('id')-> where(['user_id' => Auth::id()])->first();

        if(Contact::where(['restaurant_id' => $restaurant['id']])->count() != 0) {
            $contact = Contact::where(['restaurant_id' => $restaurant['id']])->first();

            if(empty($contact)) {
                return response()->json(['message' => 'Contato não encotrado'], 404);
            }

            $contact->fill($validatedData);
            $contact->save();
            return $contact;
        }

        $validatedData['restaurant_id'] = $restaurant['id'];

        return Contact::create($validatedData);
    }

    public function getContactByUser() {
        $contact = Contact::where(['restaurant_id' => (Restaurant::select('id')-> where(['user_id' => Auth::id()])->first())['id']])->first();

        if(empty($contact)) {
            return response()->json(['message' => 'Contato não encotrado'], 404);
        }

        return $contact;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'whatsapp' => 'string|max:255|celular_com_ddd',
            'email' => 'string|max:255|email',
            'facebook' => 'string|max:255|url',
            'instagram' => 'string|max:255|url',
            'youtube' => 'string|max:255|url',
            'linkedin' => 'string|max:255|url',
            'twiter' => 'string|max:255|url',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->fill($validatedData);
        $contact->save();
        return $contact;
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
    }
}
