<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (!Auth::check()) {
            return redirect()->route('panel');
        }

        $user = User::find(Auth::user()->id);
        return view('profile.index', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $profile)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $profile->id,
            'password' => 'nullable'
        ]);

        if (empty($request->password)) {
            $request = Arr::except($request, array('password'));
        } else {
            $fieldHash = Hash::make($request->password);
            $request->merge(['password' => $fieldHash]);
        }

        $profile->update($request->all());

        return redirect()->route('profile.index')->with('success', 'Cambios Realizados con Exito');
    }

}
