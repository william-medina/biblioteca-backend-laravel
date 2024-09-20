<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    public function login(LoginRequest $request) 
    {
        // Validar registro
        $data = $request->validated();

        // Revisar password
        if(!Auth::attempt($data)) {
            return response()->json([
                'error' => 'El Email o password son incorrectos'
            ], 422);
        }

        // Autenticar usuario
        $user = Auth::user();


        // Retornar respuesta
        return $user->createToken('token')->plainTextToken;

    }
    
    public function getCurrentUser(Request $request) 
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function updatePassword(Request $request) 
    {

            // Buscar el usuario por su email
            $user = User::where('email', $request->email)->first();
  
            if (!$user) {
                return response()->json([
                    'error' => 'Usuario no encontrado'
                ], 404);
            }


            $user->password = $request->password;
            $user->save(); 

        return 'Password Actualizado';

    }
    
}
