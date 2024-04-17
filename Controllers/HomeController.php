<?php

/**
 * Clase HomeController
 * 
 * Esta clase representa el controlador de la página de inicio.
 */
class HomeController
{
    /**
     * Método index
     * 
     * Este método maneja la solicitud de la página de inicio y devuelve una respuesta en formato JSON con un mensaje de bienvenida.
     */
    public function index()
    {
        // Retornamos la respuesta del API en formato JSON con un mensaje de bienvenida 
        http_response_code(201);
        header('Content-Type: application/json');
        $data = [
            'message' => 'Bienvenido a la página de inicio',
            'data' => 'Esta es la página de inicio de la aplicación',
            'routes' => [
                'Para acceder a la API, utiliza las siguientes rutas:',
                '- GET /api/empleados: Obtener la lista de empleados',
                '- POST /api/empleados/create: Crear un nuevo empleado',
                '- POST /api/empleados/search: Buscar empleados con los parametros especificados'
            ]
        ];
        echo json_encode($data);
    }
}
