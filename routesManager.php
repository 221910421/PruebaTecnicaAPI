<?php

/**
 * Clase RoutesManager
 *
 * Esta clase se encarga de manejar las rutas y ejecutar el controlador y acción correspondientes
 * basándose en la URL solicitada y el método HTTP utilizado.
 */
class RoutesManager
{
    /**
     * Método run
     *
     * Este método es el punto de entrada para el enrutamiento de las solicitudes.
     * Obtiene la ruta de la URL solicitada, verifica si está definida en la lista de rutas,
     * valida el método HTTP utilizado y autentica el token de autorización.
     * Luego, despacha la solicitud al controlador y acción correspondientes.
     */
    public function run()
    {
        // Obtener la ruta de la URL solicitada
        $request_uri = $_SERVER['REQUEST_URI'];

        // Eliminar cualquier parámetro de consulta de la URL
        $route = strtok($request_uri, '?');

        // Definir las rutas y los métodos asociados
        $routes = array(
            "/" => array("method" => "GET", "controller" => "HomeController", "action" => "index"),
            "/api/empleados" => array("method" => "GET", "controller" => "EmpleadosController", "action" => "index"),
            "/api/empleados/create" => array("method" => "POST", "controller" => "EmpleadosController", "action" => "store"),
            "/api/empleados/search" => array("method" => "POST", "controller" => "EmpleadosController", "action" => "search"),
        );

        // Obtener el método HTTP de la solicitud
        $method = $_SERVER['REQUEST_METHOD'];

        // Verificar si la ruta está definida y si el método HTTP es válido
        if (array_key_exists($route, $routes)) {
            $allowedMethod = $routes[$route]['method'];
            if ($method === $allowedMethod) {
                // Verificar la autenticación del token
                if ($this->authenticateToken()) {
                    $this->dispatch($routes[$route]['controller'], $routes[$route]['action'], $method);
                } else {
                    // Si la autenticación falla, retornar un error 401 Unauthorized
                    http_response_code(401);
                    include_once("Errors/401.php");
                }
            } else {
                // Si el método HTTP no es permitido, retornar un error 405
                http_response_code(405);
                include_once("Errors/405.php");
            }
        } else {
            // Si la ruta no está definida, retornar un error 404
            http_response_code(404);
            include_once("Errors/404.php");
        }
    }

    /**
     * Método dispatch
     *
     * Este método se encarga de incluir el archivo del controlador correspondiente,
     * crear una instancia del controlador y llamar al método de acción especificado.
     * Si el método de acción no existe en el controlador, se retorna un error 500.
     *
     * @param string $controller El nombre del controlador
     * @param string $action El nombre del método de acción
     * @param string $method El método HTTP utilizado
     */
    private function dispatch($controller, $action, $method)
    {
        // Incluir el archivo del controlador
        require_once "Controllers/$controller.php";

        // Crear una instancia del controlador
        $controllerInstance = new $controller();

        // Verificar si el método existe en el controlador
        if (method_exists($controllerInstance, $action)) {
            // Llamar al método del controlador
            $controllerInstance->$action();
        } else {
            // Si el método no existe en el controlador, retornar un error 500
            header("HTTP/1.0 500 Internal Server Error");
            include_once("Errors/500.php");
        }
    }

    /**
     * Método authenticateToken
     *
     * Este método se encarga de autenticar el token de autorización para las rutas del API de empleados.
     * Si la ruta no es del API de empleados, se permite el acceso sin autenticación.
     *
     * @return bool Retorna true si el token es válido, de lo contrario retorna false
     */
    private function authenticateToken()
    {
        // Token fijo predefinido
        $fixedToken = "9Rv7P3QxS6Ae2Jf4Gh5Iw8KlD";

        // Obtener la ruta de la URL solicitada
        $request_uri = $_SERVER['REQUEST_URI'];

        // Verificar si la ruta es una ruta del API de empleados
        if (strpos($request_uri, '/api/empleados') === 0) {
            // Obtener el encabezado Authorization de la solicitud
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                // Obtener el token del encabezado Authorization
                $authHeader = $headers['Authorization'];
                $token = explode(" ", $authHeader)[1]; // Separar "Bearer" del token

                // Comparar el token proporcionado con el token fijo
                return $token === $fixedToken;
            } else {
                // Si no se proporciona ningún token de autorización, denegar acceso
                return false;
            }
        }
        // Permitir el acceso sin autenticación para rutas que no son del API de empleados
        return true;
    }
}
