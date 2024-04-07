<?php

namespace Model; // Definir un espacio de nombres para la clase

// Heredar la clase ActiveRecord
class CitaServicio extends ActiveRecord {

    // Base de datos
    protected static $tabla = 'citasservicios'; // Nombre de la tabla en la BD
    protected static $columnasDB = [
        'id', // campo id de la tabla citasservicios
        'citaId', // campo citaId de la tabla citasservicios
        'servicioId' // campo servicioId de la tabla citasservicios
    ]; // Columnas de la tabla citasservicios de la BD

    // Variables
    public $id; // Atributo id de la tabla citasservicios
    public $citaId; // Atributo citaId de la tabla citasservicios
    public $servicioId; // Atributo servicioId de la tabla citasservicios

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Si existe un id lo asigna, de lo contrario asigna null
        $this->citaId = $args['citaId'] ?? ''; // Si existe un citaId lo asigna, de lo contrario asigna vacío
        $this->servicioId = $args['servicioId'] ?? ''; // Si existe un servicioId lo asigna, de lo contrario asigna vacío
    }
}