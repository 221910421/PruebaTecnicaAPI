<?php

/**
 * Este archivo representa una página de error 405 (Método no permitido).
 * 
 * Establece el código de respuesta HTTP a 405 y el tipo de contenido a JSON.
 * Luego, imprime un mensaje de error en formato JSON indicando que el método de la petición no está permitido para la ruta solicitada.
 */

// Seteamos el código de respuesta HTTP a 405
http_response_code(405);

// Seteamos el tipo de contenido a JSON
header('Content-Type: application/json');

echo json_encode([
    'error' => 'Método no permitido',
    'message' => 'El método de la petición no está permitido para la ruta solicitada'
]);
