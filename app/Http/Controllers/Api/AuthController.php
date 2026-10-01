<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // 1. SIGN UP : créer un compte
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'full_name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Compte créé avec succès',
                'user' => $user,
                'tokens' => $this->createTokens($user),
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Erreur register', ['exception' => $e]);
            return $this->serverError("Une erreur est survenue lors de l'inscription.", $e);
        }
    }

    // 2. SIGN IN : se connecter
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $user = User::where('email', $data['email'])->first();

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email ou mot de passe incorrect',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'user' => $user,
                'tokens' => $this->createTokens($user),
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur login', ['exception' => $e]);
            return $this->serverError('Une erreur est survenue lors de la connexion.', $e);
        }
    }

    // 3. REFRESH : obtenir de nouveaux tokens
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $user->currentAccessToken()->delete();                     // supprime le refresh utilisé
            $user->tokens()->where('name', 'access_token')->delete();  // supprime les anciens access tokens

            return response()->json([
                'success' => true,
                'message' => 'Tokens renouvelés',
                'tokens' => $this->createTokens($user),
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur refresh', ['exception' => $e]);
            return $this->serverError('Une erreur est survenue lors du renouvellement des tokens.', $e);
        }
    }

    // 4. ME : voir l'utilisateur connecté
    public function me(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'user' => $request->user(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur me', ['exception' => $e]);
            return $this->serverError('Une erreur est survenue.', $e);
        }
    }

    // 5. LOGOUT : se déconnecter de cet appareil
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Déconnecté de cet appareil',
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur logout', ['exception' => $e]);
            return $this->serverError('Une erreur est survenue lors de la déconnexion.', $e);
        }
    }

    // 6. LOGOUT ALL : se déconnecter de tous les appareils
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Déconnecté de tous les appareils',
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur logoutAll', ['exception' => $e]);
            return $this->serverError('Une erreur est survenue lors de la déconnexion.', $e);
        }
    }

    // Crée l'access token (15 min) et le refresh token (7 jours)
    private function createTokens(User $user): array
    {
        $accessToken = $user->createToken('access_token', ['access-api'], now()->addMinutes(15));
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], now()->addDays(7));

        return [
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => 900,
        ];
    }

    // Réponse commune pour les erreurs imprévues (500)
    private function serverError(string $message, \Throwable $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
}
