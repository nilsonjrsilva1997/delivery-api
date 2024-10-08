<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserData()
    {
        return User::where(['id' => Auth::id()])
            ->with('restaurant')
            ->with('addresses')
            ->first();
    }
}
