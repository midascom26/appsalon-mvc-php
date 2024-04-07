<?php

namespace Model; // Definir un espacio de nombres para la clase

// Heredar de la clase ActiveRecord
class Servicio extends ActiveRecord {

    // Base de datos
    protected static $tabla = 'servicios'; // Nombre de la tabla en la base de datos
    protected static $columnasDB = [
        'id', // campo id de la tabla servicios
        'nombre', // campo nombre de la tabla servicios
        'precio', // campo precio de la tabla servicios
    ]; // Columnas de la tabla servicios de la base de datos

    // Variables
    public $id; // Atributo id de la tabla servicios
    public $nombre; // Atributo nombre de la tabla servicios
    public $precio; // Atributo precio de la tabla servicios

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Si existe un id lo asigna, de lo contrario asigna null
        $this->nombre = $args['nombre'] ?? ''; // Si existe un nombre lo asigna, de lo contrario asigna vacío
        $this->precio = $args['precio'] ?? ''; // Si existe un precio lo asigna, de lo contrario asigna vacío
    }

    // Validación
    public function validar() {
        if(!$this->nombre) { // Si no hay un nombre
            self::$alertas['error'][] = "El Nombre del Servicio es Obligatorio"; // Agregar un mensaje de error
        }

        if(!$this->precio) { // Si no hay un precio
            self::$alertas['error'][] = "El Precio del Servicio es Obligatorio"; // Agregar un mensaje de error
        }

        if(!is_numeric($this->precio)) { // Si el precio no es un número
            self::$alertas['error'][] = "El Precio no es válido"; // Agregar un mensaje de error
        }

        return self::$alertas; // Retornar las alertas
    }
}