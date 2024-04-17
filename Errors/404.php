<?php

/**
 * Este archivo muestra una respuesta HTTP 404 con un mensaje de error en formato JSON.
 */

// Seteamos el código de respuesta HTTP a 404 
http_response_code(404);

// Seteamos el tipo de contenido a JSON
header('Content-Type: application/json');

// Creamos un array con el mensaje de error
$error = [
    'error' => 'Pagina no encontrada',
    'message' => 'Parece que estas intentando acceder a un recurso no existente que no existe'
];

// Convertimos el array a JSON y lo mostramos
echo json_encode($error);
