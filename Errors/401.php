<?php
http_response_code(401);

header('Content-Type: application/json');

$error = [
    'error' => 'Unauthorized',
    'message' => 'No tienes permiso para acceder a este recurso',
];

echo json_encode($error);
