<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public  function  create()
    {
        return view('register.create');
    }
    public  function  store()
    {
        //CREATE USER
        $attributes = request()->validate([
            'username' => 'required|min:3|max:255|unique:users,username',
            'email'  => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:7|max:255'
        ]);

        $user =  User::create($attributes);
        auth()->login($user);
        //lgo the user in
        return redirect('/')->with('success','Your account has been created');
    }

}
