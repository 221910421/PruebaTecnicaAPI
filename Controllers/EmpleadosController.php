<?php

/**
 * FILEPATH: /C:/Users/Miguel Dominguez/Desktop/Prueba tecnica/Controllers/EmpleadosController.php
 *
 * Clase EmpleadosController
 *
 * Esta clase se encarga de manejar las solicitudes relacionadas con los empleados.
 */
class EmpleadosController
{
    /**
     * Recupera todos los datos de los empleados de un archivo JSON y los devuelve como una respuesta JSON.
     *
     * @return void
     */
    public function index()
    {
        // Obtenemos los datos del archivo JSON
        $filename = 'empleados_data.txt';

        // Intentamos leer el archivo JSON
        $jsonData = file_get_contents($filename);

        // Verificamos si la lectura del archivo fue exitosa
        if ($jsonData === false) {
            // Si hubo un error al leer el archivo, respondemos con un mensaje de error
            http_response_code(500); // 500 Internal Server Error
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Error al obtener los datos'
            ]);
            return;
        }

        // Intentamos decodificar los datos JSON
        $data = json_decode($jsonData, true);

        // Verificamos si la decodificación del JSON fue exitosa
        if ($data === null) {
            // Si el json esta vacio, respondemos con un mensaje de error
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'No se encontraron datos'
            ]);
            return;
        }

        // Respondemos con los datos obtenidos
        header('Content-Type: application/json');
        echo json_encode([
            'code' => 200,
            'message' => 'Datos obtenidos correctamente',
            'data' => $data,
        ]);
    }

    /**
     * Almacena los datos del empleado recibidos en un archivo JSON y devuelve un mensaje de éxito como respuesta JSON.
     *
     * @return void
     */
    public function store()
    {
        // Verificamos si se han recibido datos
        $input = file_get_contents('php://input');
        if (!empty($input)) {
            // Decodificamos los datos JSON recibidos
            $data = json_decode($input, true);

            // Generamos un ID único
            $data['id'] = uniqid();

            // Validamos los datos
            $errors = $this->validateData($data);
            if (!empty($errors)) {
                // Si hay errores de validación, respondemos con un mensaje de error
                http_response_code(400); // 400 Bad Request
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => $errors
                ]);
                return;
            }

            // Guardamos los datos en un archivo JSON
            $filename = 'empleados_data.txt';
            $jsonData = file_get_contents($filename);
            $existingData = json_decode($jsonData, true) ?? [];
            $existingData[] = $data;
            $jsonData = json_encode($existingData, JSON_PRETTY_PRINT);
            file_put_contents($filename, $jsonData);

            // Respondemos con un mensaje de éxito
            http_response_code(201); // 201 Created
            header('Content-Type: application/json');
            echo json_encode([
                'message' => 'Datos recibidos correctamente y guardados en el archivo JSON',
                'data' => $data // Opcional: Puedes devolver los datos recibidos como confirmación
            ]);
        } else {
            // Si no se recibieron datos, respondemos con un error
            http_response_code(400); // 400 Bad Request
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'No se recibieron datos'
            ]);
        }
    }

    /**
     * Busca empleados basados en los criterios de búsqueda proporcionados y devuelve los resultados coincidentes como una respuesta JSON.
     * Si no se proporcionan criterios de búsqueda, devuelve todos los datos de los empleados.
     *
     * @return void
     */
    public function search()
    {
        // Verificamos si se han recibido datos por POST
        if (!empty($_POST)) {
            // Obtenemos los datos del formulario
            $fechaInicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
            $fechaFin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
            $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;

            // Validamos los datos de búsqueda recibidos guardándolos en un array 
            $errors = $this->validateSearchData($fechaInicio, $fechaFin, $telefono);

            // Si hay errores de validación, respondemos con un mensaje de error
            if (!empty($errors)) {
                http_response_code(400); // 400 Bad Request
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => $errors
                ]);
                return;
            }

            $filteredData = $this->filterData($fechaInicio, $fechaFin, $telefono);

            // Si no se encontraron resultados, respondemos con un mensaje de error
            if (count($filteredData) === 0) {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'No se encontraron resultados'
                ]);
                return;
            }

            // Respondemos con los datos obtenidos
            header('Content-Type: application/json');
            echo json_encode([
                'code' => 200,
                'message' => 'Datos obtenidos correctamente',
                'data' => $filteredData,
            ]);
        } else {
            // Si no se recibieron datos, devolvemos todos los empleados
            $filename = 'empleados_data.txt';
            $jsonData = file_get_contents($filename);

            // Intentamos decodificar los datos JSON
            $data = json_decode($jsonData, true);

            if ($data === null) {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'No se encontraron datos válidos'
                ]);
                return;
            }

            // Respondemos con todos los datos obtenidos
            header('Content-Type: application/json');
            echo json_encode([
                'code' => 200,
                'message' => 'Datos obtenidos correctamente',
                'data' => $data,
                'request' => $_POST // Retornamos todos los datos de la solicitud
            ]);
        }
    }

    /**
     * Filtra los datos de los empleados según las condiciones de búsqueda.
     *
     * @param string|null $fechaInicio La fecha de inicio para filtrar los empleados (opcional).
     * @param string|null $fechaFin La fecha de fin para filtrar los empleados (opcional).
     * @param string|null $telefono El número de teléfono para filtrar los empleados (opcional).
     * @return array Los datos de los empleados filtrados.
     */
    private function filterData($fechaInicio, $fechaFin, $telefono)
    {
        // Obtenemos los datos del archivo JSON
        $filename = 'empleados_data.txt';

        // Intentamos leer el archivo JSON
        $jsonData = file_get_contents($filename);

        // Verificamos si la lectura del archivo fue exitosa
        if ($jsonData === false) {
            // Si hubo un error al leer el archivo, retornamos un array vacío
            return [];
        }

        // Intentamos decodificar los datos JSON
        $data = json_decode($jsonData, true);

        // Verificamos si la decodificación del JSON fue exitosa
        if ($data === null) {
            // Si el JSON está vacío o no es válido, retornamos un array vacío
            return [];
        }

        // Filtramos los empleados que cumplan con las condiciones de búsqueda
        $filteredData = array_filter($data, function ($empleado) use ($fechaInicio, $fechaFin, $telefono) {
            if (
                $fechaInicio !== null && $fechaInicio !== "" && $empleado['fecha'] < $fechaInicio
            ) {
                return false;
            }
            if (
                $fechaFin !== null && $fechaFin !== "" && $empleado['fecha'] > $fechaFin
            ) {
                return false;
            }
            if ($telefono !== null && $telefono !== "" && $empleado['telefono'] !== $telefono) {
                return false;
            }
            return true;
        });

        return $filteredData;
    }


    /**
     * Valida los criterios de búsqueda proporcionados para la búsqueda de empleados.
     *
     * @param string|null $fechaInicio La fecha de inicio para la búsqueda.
     * @param string|null $fechaFin La fecha de fin para la búsqueda.
     * @param string|null $telefono El número de teléfono para la búsqueda.
     * @return array Un array de errores de validación, si los hay.
     */
    private function validateSearchData($fechaInicio, $fechaFin, $telefono)
    {
        $errors = [];

        // Validar el formato de la fecha de inicio (formato yyyy-mm-dd)
        if ($fechaInicio !== null && $fechaInicio !== "" && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio)) {
            $errors[] = 'La fecha de inicio es inválida';
        }

        // Validar el formato de la fecha de fin (formato yyyy-mm-dd)
        if ($fechaFin !== null && $fechaFin !== "" && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
            $errors[] = 'La fecha de fin es inválida';
        }

        // Validar que el teléfono tenga 10 dígitos numéricos
        if ($telefono !== null && $telefono !== "" && !preg_match('/^\d{10}$/', $telefono)) {
            $errors[] = 'El teléfono es inválido';
        }

        return $errors;
    }

    /**
     * Valida los datos recibidos para almacenar un empleado.
     *
     * @param array $data Los datos del empleado a validar.
     * @return array Un array de errores de validación, si los hay.
     */
    private function validateData($data)
    {
        $errors = [];

        // Validar que el nombre no esté vacío y contenga solo letras, espacios y acentos
        if (empty($data['nombre']) || !preg_match('/^[a-zA-Z\sáéíóúÁÉÍÓÚüÜñÑ]+$/', $data['nombre'])) {
            $errors[] = 'El nombre es inválido';
        }

        // Validar que el teléfono tenga 10 dígitos numéricos
        if (empty($data['telefono']) || !preg_match('/^\d{10}$/', $data['telefono'])) {
            $errors[] = 'El teléfono es inválido';
        }

        // Validar que el cargo no esté vacío
        if (empty($data['cargo'])) {
            $errors[] = 'El cargo no puede estar vacío';
        }

        // Validar el formato de la fecha (formato yyyy-mm-dd)
        if (empty($data['fecha']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha'])) {
            $errors[] = 'La fecha de nacimiento es inválida';
        }

        // Validar el género (debe ser Masculino, Femenino u Otro)
        $generosPermitidos = ['Masculino', 'Femenino', 'Otro'];
        if (isset($data['genero']) && !in_array($data['genero'], $generosPermitidos)) {
            $errors[] = 'El género es inválido';
        }

        // Validamos que el estado de solicitud no esté vacío y contenga solo letras, espacios y acentos
        if (empty($data['EdoSolicitud']) || !preg_match('/^[a-zA-Z\sáéíóúÁÉÍÓÚüÜñÑ]+$/', $data['EdoSolicitud'])) {
            $errors[] = 'El estado de solicitud es inválido';
        }

        return $errors;
    }
}
