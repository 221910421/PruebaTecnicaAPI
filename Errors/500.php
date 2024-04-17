<?php

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
