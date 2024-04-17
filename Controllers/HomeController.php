<?php

class HomeController
{
    public function index()
    {
        //retornamos la respuesta del api en formato json con un mensaje de bienvenida 
        http_response_code(201);
        header('Content-Type: application/json');
        $data = [
            'message' => 'Bienvenido a la página de inicio',
            'data' => 'Esta es la página de inicio de la aplicación'
        ];
        echo json_encode($data);
    }
}
