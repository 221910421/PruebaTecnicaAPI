<?php

/**
 * Este archivo representa una respuesta HTTP 401 (No autorizado).
 * Establece el código de respuesta HTTP a 401 y el tipo de contenido a JSON.
 * Luego, genera y muestra un mensaje de error en formato JSON.
 */

http_response_code(401);

header('Content-Type: application/json');

$error = [
    'error' => 'Unauthorized',
    'message' => 'No tienes permiso para acceder a este recurso',
];

echo json_encode($error);
