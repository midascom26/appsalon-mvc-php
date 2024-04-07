<?php

namespace Controllers; // Definir un espacio de nombres para la clase

use MVC\Router; // Importar la clase Router
use Model\Servicio; // Importar la clase Servicio

// Controlador de Servicios
class ServicioController {
    
    // ------------------------- index ----------------------------
    public static function index(Router $router) {
        session_start(); // Iniciar la sesión - Accedemos a los datos del usuario y tenemos acceso a la variable $_SESSION
        isAdmin(); // Verificar si el usuario es administrador - Proteger la ruta
        $servicios = Servicio::all(); // Obtener todos los servicios

        // Mostrar la vista servicios/index
        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'], // Pasar el nombre del usuario a la vista
            'servicios' => $servicios // Pasar los servicios a la vista
        ]);
    }
    // ------------------------------------------------------------

    // ------------------------- crear ----------------------------
    public static function crear(Router $router) {
        session_start(); // Iniciar la sesión - Accedemos a los datos del usuario y tenemos acceso a la variable $_SESSION
        isAdmin(); // Verificar si el usuario es administrador - Proteger la ruta
        $servicio = new  Servicio; // Crear un nuevo servicio
        $alertas = []; // Inicializar un array de alertas

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST); // Sincronizar los datos del formulario con el objeto servicio
            $alertas = $servicio->validar(); // Validar los datos del formulario

            if(empty($alertas)) { // Si no hay alertas
                $servicio->guardar(); // Guardar el servicio en la base de datos
                header('Location: /servicios'); // Redirigir a la página servicios
            }
        }

        // Mostrar la vista servicios/crear
        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],// Pasar el nombre del usuario a la vista
            'servicio' => $servicio, // Pasar el servicio a la vista
            'alertas' => $alertas // Pasar las alertas a la vista
        ]);
    }
    // ------------------------------------------------------------

    // ------------------------- actualizar -----------------------
    public static function actualizar(Router $router) {
        session_start(); // Iniciar la sesión - Accedemos a los datos del usuario y tenemos acceso a la variable $_SESSION
        isAdmin(); // Verificar si el usuario es administrador - Proteger la ruta

        if(!is_numeric($_GET['id'])) { // Si no es un número
            return; // Si no hay un id, salir de la función
        }

        $servicio = Servicio::find($_GET['id']); // Buscar el servicio por el id
        $alertas = []; // Inicializar un array de alertas

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST); // Sincronizar los datos del formulario con el objeto servicio
            $alertas = $servicio->validar(); // Validar los datos del formulario

            if(empty($alertas)) { // Si no hay alertas
                $servicio->guardar(); // Guardar el servicio en la base de datos
                header('Location: /servicios'); // Redirigir a la página servicios
            }
        }

        // Mostrar la vista servicios/actualizar
        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'], // Pasar el nombre del usuario a la vista
            'servicio' => $servicio, // Pasar el servicio a la vista
            'alertas' => $alertas // Pasar las alertas a la vista
        ]);
    }
    // ------------------------------------------------------------

    // ------------------------- eliminar -------------------------
    public static function eliminar() { // No se requiere Router porque no se direcciona a una vista
        session_start(); // Iniciar la sesión - Accedemos a los datos del usuario y tenemos acceso a la variable $_SESSION
        isAdmin(); // Verificar si el usuario es administrador - Proteger la ruta

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id']; // Obtener el id del servicio a eliminar
            $servicio = Servicio::find($id); // Buscar el servicio por el id
            $servicio->eliminar(); // Eliminar el servicio de la base de datos
            header('Location: /servicios'); // Redirigir a la página servicios
        }
    }
    // ------------------------------------------------------------
}
