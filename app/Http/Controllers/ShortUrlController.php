<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use App\Models\ShortUrl;

class ShortUrlController extends Controller
{
    // Agregar el middleware de autenticación en el constructor
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        // Validar la entrada
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'URL inválida'], 400);
        }

        // Verificar si la cadena es válida
        $url = $request->input('url');
        $isValid = $this->isValid($url);

        if (!$isValid) {
            return response()->json(['error' => 'Cadena no válida'], 400);
        }

        // Generar URL corta utilizando la API de TinyURL
        $shortUrl = $this->generateShortUrl($url);

        // Guardar la URL original y la URL corta en la base de datos
        $shortUrlRecord = ShortUrl::create([
            'original_url' => $url,
            'short_url' => $shortUrl,
        ]);

        // Construir la URL de redirección
        $redirectUrl = $shortUrl;



        return response()->json(['url' => $redirectUrl], 201, [], JSON_UNESCAPED_SLASHES);

    }

    public function redirect($shortUrl)
    {
        // Recuperar la URL original asociada a la URL corta desde la base de datos
        $shortUrlRecord = ShortUrl::where('short_url', $shortUrl)->first();

        if (!$shortUrlRecord) {
            abort(404);
        }

        return redirect()->away($shortUrlRecord->original_url);
    }

    private function generateShortUrl($url)
    {
        $client = new Client();

        // Llamar a la API de TinyURL para generar la URL corta
        $response = $client->get('https://tinyurl.com/api-create.php?url=' . urlencode($url));

        if ($response->getStatusCode() === 200) {
            $shortUrl = $response->getBody()->getContents();
            return $shortUrl;
        }

        // En caso de error, puedes implementar una lógica de generación de URL corta alternativa aquí
        // o manejar el error de otra forma según tus necesidades

        return null;
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

        // Si la pila está vacía después de recorrer la cadena, la cadena es válida
        if (empty($stack)) {
            return true;
        }

// Si no está vacía, la cadena no es válida
        return false;
    }
}
