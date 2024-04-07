<?php

namespace Model; // Definir un espacio de nombres para la clase

// Heredar la clase ActiveRecord
class Cita extends ActiveRecord {

    // Base de datos
    protected static $tabla = 'citas'; // Nombre de la tabla en la base de datos
    protected static $columnasDB = [
        'id', // campo id de la tabla citas
        'fecha', // campo fecha de la tabla citas
        'hora', // campo hora de la tabla citas
        'usuarioId', // campo usuarioId de la tabla citas
    ]; // Columnas de la tabla citas de la base de datos

    // Variables
    public $id; // Atributo id de la tabla citas
    public $fecha; // Atributo fecha de la tabla citas
    public $hora; // Atributo hora de la tabla citas
    public $usuarioId; // Atributo usuarioId de la tabla citas

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Si existe un id lo asigna, de lo contrario asigna null
        $this->fecha = $args['fecha'] ?? ''; // Si existe una fecha lo asigna, de lo contrario asigna vacío
        $this->hora = $args['hora'] ?? ''; // Si existe una hora lo asigna, de lo contrario asigna vacío
        $this->usuarioId = $args['usuarioId'] ?? ''; // Si existe un usuarioId lo asigna, de lo contrario asigna vacío
    }

    //-------------------------validar--------------------------------

    // Mensajes de validación de errores para la creación
}