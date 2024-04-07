<?php

require_once __DIR__ . '/../includes/app.php'; // Requerir el archivo app.php

use Controllers\AdminController; // Importar la clase AdminController
use Controllers\APIController; // Importar la clase APIController
use Controllers\CitaController; // Importar la clase CitaController
use Controllers\LoginController; // Importar la clase LoginController
use Controllers\ServicioController; // Importar la clase ServicioController
use MVC\Router; // Importar la clase Router

$router = new Router();

//------------- DEFINICION DEL ROUTING ------------------------

// Iniciar Sesión
$router->get('/', [LoginController::class, 'login']); // vista / para iniciar sesión
$router->post('/', [LoginController::class, 'login']); // Formulario para iniciar sesión
$router->get('/logout', [LoginController::class, 'logout']); // Cerrar sesión

// Recuperar password
$router->get('/olvide', [LoginController::class, 'olvide']); // vista olvide
$router->post('/olvide', [LoginController::class, 'olvide']); // Formulario olvide
$router->get('/recuperar', [LoginController::class, 'recuperar']); // vista recuperar
$router->post('/recuperar', [LoginController::class, 'recuperar']); // Formulario recuperar

// Crear cuenta
$router->get('/crear-cuenta', [LoginController::class, 'crear']); // vista crear-cuenta
$router->post('/crear-cuenta', [LoginController::class, 'crear']); // Formulario crear-cuenta

// Confirmar cuenta
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']); // vista confirmar-cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']); // vista confirmar-cuenta

// Area Privada (Usuarios Registrados) - Para esto requerimos una cuenta y haber iniciado sesión
$router->get('/cita', [CitaController::class, 'index']); // vista cita
$router->get('/admin', [AdminController::class, 'index']); // vista admin

// API de Citas
$router->get('/api/servicios', [APIController::class, 'index']); // vista api/servicios - contiene los servicios en formato JSON
$router->post('/api/citas', [APIController::class, 'guardar']); // Formulario api/citas
$router->post('/api/eliminar', [APIController::class, 'eliminar']); // Formulario api/eliminar

// CRUD de Servicios
$router->get('/servicios', [ServicioController::class, 'index']); // vista /servicios
$router->get('/servicios/crear', [ServicioController::class, 'crear']); // vista /servicios/crear
$router->post('/servicios/crear', [ServicioController::class, 'crear']); // formulario /servicios/crear
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']); // vista /servicios/actualizar
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']); // formulario /servicios/actualizar
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']); // formulario /servicios/eliminar

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
