# Prueba Tecnica creación y consulta de apis

Este proyecto es una prueba técnica que se enfoca en la creación y consulta de un API, el cual nos permite gestionar un módulo llamado empleados. Con este API, se pueden crear empleados y consultar la información de los empleados registrada, utilizando diferentes términos de búsqueda, así como la protección de rutas mediante un bearer token.

## Requerimientos del sistema

- PHP 8.0 o superior
- Postman (opcional, para la consulta de apis)

## Uso del proyecto

1. Navega al directorio del proyecto:

   ```
   cd "Prueba Tecnica"
   ```

2. Ejecuta el siguiente comando para iniciar el servicio

   ```
   php -S localhost:8001
   ```

## Como consultar el api

Se incluyen dos maneras de consultar el api, ya se importando la colección de consulta de postman ya incluida en el proyecto o consultarlas por su cuenta

## Como consultar el api

Se incluyen dos maneras de consultar el api, ya sea importando la colección de consulta de postman ya incluida en el proyecto o consultarlas por su cuenta.

### Consultar con la colección incluida

1. **Importar la colección en Postman**:

   - Abre Postman.
   - En la barra lateral izquierda, haz clic en el botón "Importar".
   - Selecciona la opción "Subir archivos" y selecciona el archivo de colección incluido en el proyecto.
   - Una vez importada la colección, verás que aparece en la barra lateral izquierda de Postman.

2. **Ejecutar las consultas**:

   - Abre la colección importada en la barra lateral izquierda.
   - Dentro de la colección, verás diferentes solicitudes organizadas por grupos.
   - Haz clic en una solicitud para abrirla y ver los detalles, como la URL y el método HTTP utilizado.
   - Para ejecutar la solicitud, haz clic en el botón "Enviar" en la esquina superior derecha de la solicitud.
   - Verás la respuesta del servidor en la sección de respuesta debajo de la solicitud.

3. **Explorar las consultas**:
   - Dentro de la colección, puedes explorar diferentes solicitudes para crear y consultar empleados.
   - Cada solicitud puede tener parámetros adicionales o información específica que puedes ajustar según tus necesidades.
   - Puedes ejecutar varias solicitudes en secuencia para probar diferentes funciones del API.

### Consultar manualmente

Si no deseas usar la colección ya incluida en el proyecto, aún puedes hacerlo manualmente. Para este caso, solo se te proporcionarán las rutas y sus métodos para que las puedas consultar desde cualquier servicio que prefieras. Además, en ciertas rutas se necesita el uso de un token de autenticación: "9Rv7P3QxS6Ae2Jf4Gh5Iw8KlD".

1. **Lista de rutas y sus métodos**:
   - `GET /`: Esta ruta nos retorna un mensaje de bienvenida así como las rutas presentes en el proyecto.
   - `GET /api/empleados`: Esta ruta nos retorna a todos los empleados guardados en el archivo txt. Esta ruta está protegida y necesitarás el token de autenticación.
   - `POST /api/empleados/create`: Esta ruta nos permite crear un empleado. Recibe un JSON con la siguiente estructura: `{ "nombre": "", "telefono": "", "cargo": "", "fecha": "", "genero": "", "EdoSolicitud": "" }`.
   - `POST /api/empleados/search`: Esta es una ruta para buscar un empleado utilizando los siguientes datos: `"fecha_inicio"`, `"fecha_fin"`, `"telefono"`.
