<?php

namespace Controllers; // Definir un espacio de nombres para la clase

use MVC\Router; // Importar la clase Router

class CitaController
{

    //-----------------------------index--------------------------------------

    public static function index(Router $router)
    {
        session_start(); // Iniciar la sesión - Accedemos a los datos del usuario y tenemos acceso a la variable $_SESSION

        isAuth(); // Verificar si el usuario está autenticado

        // Mostrar la vista cita/index
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'], // Pasar el nombre del usuario a la vista
            'id' => $_SESSION['id'] // Pasar el id del usuario a la vista
        ]);
    }
    //------------------------------------------------------------------------
}