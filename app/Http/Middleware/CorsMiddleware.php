<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        // Leer la configuración desde la variable de entorno
        $allowAllOrigins = env('ALLOW_ALL_ORIGINS');
        

        // Permitir todas las solicitudes si se permite todos los orígenes
        if ($allowAllOrigins) {
            return $next($request);
        }
        
        $allowedOrigins = explode(',', env('ALLOWED_ORIGINS', ''));

        // Verificar si el Origin de la solicitud está permitido
        if (in_array($request->header('Origin'), $allowedOrigins)) {
            return $next($request);
        }

        // Permitir acceso a rutas específicas como las de imágenes
        if ($request->is('api/covers/*')) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
