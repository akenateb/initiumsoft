<?php
use App\Http\Controllers\YourController; // Asegúrate de importar tu controlador

test('La función isValid devuelve el resultado esperado', function () {
$controller = new YourController(); // Reemplaza `YourController` por el nombre de tu controlador

// Prueba una cadena válida
$validString = '{}';
expect($controller->isValid($validString))->toBeTrue();

// Prueba una cadena inválida
$invalidString = '{[}]';
expect($controller->isValid($invalidString))->toBeFalse();

// Prueba una cadena vacía
$emptyString = '';
expect($controller->isValid($emptyString))->toBeTrue();
});
