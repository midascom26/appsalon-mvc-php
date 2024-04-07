<?php

namespace Controllers; // Definir un espacio de nombres para la clase

use Model\AdminCita; // Importar la clase AdminCita
use MVC\Router; // Importar la clase Router

// Controlador de la vista admin
class AdminController {

    public static function index(Router $router) {
        session_start(); // Iniciar la sesión - Accedemos a los datos del usuario y tenemos acceso a la variable $_SESSION

        isAdmin(); // Verificar si el usuario es administrador

        $fecha = $_GET['fecha'] ?? date('Y-m-d'); // Obtener la fecha de la URL, si no existe, obtener la fecha actual del servidor, formato año-mes-día, Ejemplo: 2021-08-25
        $fechas = explode('-', $fecha); // Convertir la fecha en un array separado por guiones, ejemplo : [2021, 08, 25]
        
        // Validar la fecha - revisa la fecha así: checkdate(mes, día, año)
        if(!checkdate($fechas[1], $fechas[2], $fechas[0])) { // Si la fecha no es válida
            header('Location: /404'); // Si la fecha no es válida, redirigir a la página 404
        }

        // Consultar la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= "usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio ";
        $consulta .= "FROM citas ";
        $consulta .= "LEFT OUTER JOIN usuarios ";
        $consulta .= "ON citas.usuarioId=usuarios.id ";
        $consulta .= "LEFT OUTER JOIN citasServicios ";
        $consulta .= "ON citasServicios.citaId=citas.id ";
        $consulta .= "LEFT OUTER JOIN servicios ";
        $consulta .= "ON servicios.id=citasServicios.servicioId ";
        $consulta .= "WHERE fecha = '$fecha'";

        //debuguear($consulta); // Imprimir la consulta (debuguear es una función personalizada que imprime la consulta en la consola del navegador

        $citas = AdminCita::SQL($consulta); // Ejecutar la consulta y obtener las citas

        // Mostrar la vista admin/index
        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'], // Pasar el nombre del usuario a la vista
            'citas' => $citas, // Pasar las citas a la vista
            'fecha' => $fecha // Pasar la fecha a la vista
        ]);
    }
}