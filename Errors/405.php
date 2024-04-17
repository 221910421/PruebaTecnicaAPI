<?php

// Seteamos el código de respuesta HTTP a 405
http_response_code(405);

// Seteamos el tipo de contenido a JSON
header('Content-Type: application/json');

echo json_encode([
    'error' => 'Método no permitido',
    'message' => 'El método de la petición no está permitido para la ruta solicitada'
]);
