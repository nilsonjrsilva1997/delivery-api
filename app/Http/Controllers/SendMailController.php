<?php

namespace App\Http\Controllers;

use App\Mail\ContactRestaurant;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendMailController extends Controller
{
    public function sendEmailContact(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:500',
            'restaurant_id' => 'required|integer|exists:restaurants,id'
        ]);


        $restaurantData = Restaurant::where(['id' => $validatedData['restaurant_id']])->with('user')->first();

        $restaurantData['logo'] = URL::to('/') . '/storage' . '/' . ($restaurantData['logo']);
        $restaurantData['banner'] = URL::to('/') . '/storage' . '/' . ($restaurantData['banner']);

        if(empty($restaurantData['user']['email'])) {
            return response()->json(['message' => 'Erro ao enviar email']);
        }

        Mail::to($restaurantData['user']['email'])->send(new ContactRestaurant($validatedData, $restaurantData));

        return response()->json(['message' => 'Email enviado com sucesso!'], 200);
    }
}
