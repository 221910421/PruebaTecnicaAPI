<?php
class EmpleadosController
{
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

    public function search()
    {
        // Verificamos si se han recibido datos por GET
        if (!empty($_GET)) {
            // Obtenemos los datos de la URL
            $fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;
            $telefono = isset($_GET['telefono']) ? $_GET['telefono'] : null;

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
                // Si el JSON está vacío o no es válido, respondemos con un mensaje de error
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'No se encontraron datos válidos'
                ]);
                return;
            }

            // Filtramos los empleados que cumplan con las condiciones de búsqueda
            $filteredData = array_filter($data, function ($empleado) use ($fechaInicio, $fechaFin, $telefono) {
                if ($fechaInicio !== null && $empleado['fecha'] < $fechaInicio) {
                    return false;
                }
                if ($fechaFin !== null && $empleado['fecha'] > $fechaFin) {
                    return false;
                }
                if ($telefono !== null && $empleado['telefono'] !== $telefono) {
                    return false;
                }
                return true;
            });

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
                'request' => $_GET // Retornamos todos los datos de la solicitud
            ]);
        }
    }

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
