<?php

/**
 * Archivo principal de la aplicación.
 * Este archivo incluye el archivo "routesManager.php" y crea una instancia de la clase "routesManager".
 * Luego, llama al método "run" de la instancia de "routesManager" para iniciar la aplicación.
 */
require_once "routesManager.php";

$routes = new routesManager();

$routes->run();
