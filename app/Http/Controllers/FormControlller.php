<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FormControlller extends Controller
{
    public function store(Request $request)
    {
        // Validate form input
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password'=>'required'
           
        ]);

        
        $data = User::create($validatedData);

        return response()->json([
            'message' => 'Data submitted successfully!',
            'data' => $data
        ], 201);
    }
}
