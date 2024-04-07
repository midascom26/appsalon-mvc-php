<?php
use Model\ActiveRecord; // Importar la clase ActiveRecord
require_once __DIR__ . '/../vendor/autoload.php'; // Requerir el autoload de Composer
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Crear una instancia de Dotenv
$dotenv->safeLoad(); // Cargar el archivo .env de forma segura para poder acceder a las variables de entorno

require_once 'funciones.php';
require_once 'database.php';

// Conectarnos a la base de datos
ActiveRecord::setDB($db);
