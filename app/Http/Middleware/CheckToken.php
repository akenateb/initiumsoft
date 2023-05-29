<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (is_null($token) || !$this->isValid($token)) {
            return response()->json(['error' => 'Token no válido'], 401);
        }

        return $next($request);
    }

    private function isValid(string $cadena): bool
    {
        $stack = [];
        $abiertos = ['{', '[', '('];
        $cerrados = ['}', ']', ')'];
        $parejas = ['{}', '[]', '()'];

        // Recorremos la cadena de entrada
        for ($i = 0; $i < strlen($cadena); $i++) {
            $caracter = $cadena[$i];

            // Si es un paréntesis, llave o corchete abierto, lo agregamos a la pila
            if (in_array($caracter, $abiertos)) {
                array_push($stack, $caracter);
            }
            // Si es un paréntesis, llave o corchete cerrado, verificamos si coincide con el último elemento de la pila
            elseif (in_array($caracter, $cerrados)) {
                // Si la pila está vacía o el último elemento de la pila no forma una pareja válida, la cadena no es válida
                if (empty($stack) || !in_array(end($stack) . $caracter, $parejas)) {
                    return false;
                }
                // Si coincide con una pareja válida, eliminamos el último elemento de la pila
                array_pop($stack);
            }
        }

        // Si la pila no está vacía al finalizar el recorrido, la cadena no es válida
        return empty($stack);
    }
}
