<?php

/**
 * Este archivo muestra una respuesta de error 500 (Error interno del servidor) en formato JSON.
 * 
 * El código establece la respuesta HTTP a 500 y el tipo de contenido a JSON.
 * Luego, crea un array con el mensaje de error y lo convierte a JSON para mostrarlo.
 */

// Seteamos el código de respuesta HTTP a 500 
http_response_code(500);

// Seteamos el tipo de contenido a JSON
header('Content-Type: application/json');

// Creamos un array con el mensaje de error
$error = [
    'error' => 'Error interno del servidor',
    'message' => 'Ha ocurrido un error interno en el servidor'
];

// Convertimos el array a JSON y lo mostramos
echo json_encode($error);
