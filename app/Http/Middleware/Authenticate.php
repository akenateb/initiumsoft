<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Verificar la autorización solo si se proporciona la cabecera de autenticación
        if ($request->hasHeader('Authorization')) {
            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);

            // Realizar la lógica de verificación del token aquí
            // Por ejemplo, verificar si el token es válido y está asociado a un usuario autenticado

            // Si la autorización falla, puedes retornar una respuesta de error con el código 401
            // o redirigir al usuario a una página de inicio de sesión, según tus necesidades

            // Ejemplo de respuesta de error:
            // return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
