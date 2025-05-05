<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException as ExceptionsTokenExpiredException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthentificateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug: Afficher l'en-tête Authorization
        $token = $request->header('Authorization');
        $debug = [
            'has_auth_header' => !empty($token),
            'auth_header' => $token,
            'api_guard_check' => auth()->guard('api')->check(),
        ];
        
        try {
            // Essayer d'obtenir le token et de le valider
            $user = JWTAuth::parseToken()->authenticate();
            $debug['token_parsed'] = true;
            $debug['user_found'] = !empty($user);
        } catch (ExceptionsT $e) {
            $debug['error'] = 'Token expiré';
        } catch (TokenInvalidException $e) {
            $debug['error'] = 'Token invalide';
        } catch (\Exception $e) {
            $debug['error'] = 'Token non trouvé ou autre erreur: ' . $e->getMessage();
        }
        
        // Retourner le résultat du debug
        return response()->json($debug);
        
        // Code original à réactiver après le debug
        /* if (auth()->guard('api')->check()) {
            return $next($request);
        }
        
        return response()->json(['error' => 'non connecté'], 403); */
    }
}