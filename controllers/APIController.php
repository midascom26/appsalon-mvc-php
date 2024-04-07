<?php

namespace Controllers; // Definir un espacio de nombres para la clase

use Model\CitaServicio; // Importar la clase CitaServicio
use Model\Cita; // Importar la clase Cita
use Model\Servicio; // Importar la clase Servicio

/*
 * En este controlador se manejan las peticiones de la API, por lo tanto como estamos trabajando con JSON, no necesitamos una vista,
 * ni tampoco utilizar la clase Router, ya que no vamos a renderizar vistas, solo vamos a devolver datos en formato JSON.
 */

// Controlador de la API
class APIController {

    //-----------------------------index--------------------------------------

    public static function index() {
        $servicios = Servicio::all(); // Obtener todos los servicios

        echo json_encode($servicios); // Convertir el array de servicios a formato JSON para mostrarlos en la vista /api/servicios
    }
    //------------------------------------------------------------------------

    //-----------------------------guardar------------------------------------

    public static function guardar() {
        // ALMACENA UNA CITA EN LA BASE DE DATOS Y DEVUELVE EL ID DE LA CITA
        $cita = new Cita($_POST); // Crear una nueva cita con los datos del formulario
        $resultado = $cita->guardar(); // Guardar la cita en la base de datos. Si el id es null, se crea un nuevo registro. Retorna el resultado true o false y el id de la cita
        
        $id = $resultado['id']; // Asignar el id de la cita al id

        // ALMACENA LOS SERVICIOS CON EL ID DE LA CITA EN LA TABLA citasservicios
        // Array de servicios que se pasa en el FormData desde app.js
        // Inicialmente vienen así: {"servicios":"3,4,6,5,7"} y los convertimos así: {"servicios":["3","4","6","5","7"]}
        // Esta conversión se hace con el fin de poderlo recorrer con un forEach
        $idServicios = explode(',', $_POST['servicios']); // Convertir los servicios en un array de strings, el separador es la coma ,

        foreach ($idServicios as $idServicio) { // Recorrer el array de servicios
            $args = [
                'citaId' => $id, // Asignar el id de la cita al campo citaId
                'servicioId' => $idServicio // Asignar el id del servicio al campo servicioId
            ];
            $citaServicio = new CitaServicio($args); // Crear un nuevo registro de citaServicio con los datos de la cita y el servicio
            $citaServicio->guardar(); // Guardar el registro de citaServicio en la base de datos
        }

        // RETORNAMOS UNA RESPUESTA
        echo json_encode(['resultado' => $resultado]); // Resultado de la consulta de la cita - Convertir el array de $respuesta a formato JSON para mostrarlos en la vista /api/citas
    }
    //------------------------------------------------------------------------

    //-----------------------------eliminar-----------------------------------
    public static function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Si el método de la petición es POST
            $id = $_POST['id']; // Obtener el id de la cita a eliminar
            $cita = Cita::find($id); // Buscar la cita por el id
            $cita->eliminar(); // Eliminar la cita de la base de datos
            
            header('Location:' . $_SERVER['HTTP_REFERER']); // Redirigir a la página anterior de donde venía el usuario
        }
    }
}

