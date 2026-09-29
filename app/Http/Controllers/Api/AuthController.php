<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    // 1. SIGN UP : créer un compte
    public function register(RegisterRequest $request)
    {
            $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return response()->json([
            'message' => 'Compte créé avec succès',
            'user' => $user,
            'tokens' => $this->createTokens($user),
        ], 201);
    }

    // 2. SIGN IN : se connecter
    public function login(LoginRequest $request)
    {
            $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect',
            ], 401);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'tokens' => $this->createTokens($user),
        ]);
    }

    // 3. REFRESH : obtenir de nouveaux tokens
    public function refresh(Request $request)
{
    $user = $request->user();

    $request->user()->currentAccessToken()->delete();          // supprime le refresh utilisé
    $user->tokens()->where('name', 'access_token')->delete();  // NOUVEAU : supprime les anciens access tokens

    return response()->json($this->createTokens($user));
}

    // 4. ME : voir l'utilisateur connecté
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    // 5. LOGOUT : se déconnecter
   public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();   // cet appareil seulement
    return response()->json(['message' => 'Déconnecté de cet appareil']);
}

public function logoutAll(Request $request)
{
    $request->user()->tokens()->delete();               // tous les appareils
    return response()->json(['message' => 'Déconnecté de tous les appareils']);
}

    // Fonction interne : crée l'access token et le refresh token
    private function createTokens(User $user): array
    {
        $accessToken = $user->createToken(
            'access_token',
            ['access-api'],
            now()->addMinutes(15)
        );

        $refreshToken = $user->createToken(
            'refresh_token',
            ['issue-access-token'],
            now()->addDays(7)
        );

        return [
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => 900,
        ];
    }
}