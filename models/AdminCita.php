<?php

namespace Model; // Definir un espacio de nombres para la clase

// Heredar la clase ActiveRecord - Clase padre - Para acceder a la BD
class AdminCita extends ActiveRecord {

    // Base de datos
    // Aquí tomamos como referencia la tabla donde se va a encontrar la mayoría de la información.
    // Tener presente que se va a crear una tabla en memoria que va a contener información de
    // todas las tablas de la BD
    protected static $tabla = 'citasservicios'; // Nombre de la tabla en la base de datos
    protected static $columnasDB = [
        'id', // campo id de la tabla que vamos a crear en memoria
        'hora', // campo hora de la tabla que vamos a crear en memoria
        'cliente', // campo cliente de la tabla que vamos a crear en memoria
        'email', // campo email de la tabla que vamos a crear en memoria
        'telefono', // campo telefono de la tabla que vamos a crear en memoria
        'servicio', // campo servicio de la tabla que vamos a crear en memoria
        'precio' // campo precio de la tabla que vamos a crear en memoria
    ]; // Columnas de la tabla que vamos a crear en memoria

    // Variables
    public $id; // Atributo id de la tabla que vamos a crear en memoria
    public $hora; // Atributo hora de la tabla que vamos a crear en memoria
    public $cliente; // Atributo cliente de la tabla que vamos a crear en memoria
    public $email; // Atributo email de la tabla que vamos a crear en memoria
    public $telefono; // Atributo telefono de la tabla que vamos a crear en memoria
    public $servicio; // Atributo servicio de la tabla que vamos a crear en memoria
    public $precio; // Atributo precio de la tabla que vamos a crear en memoria

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Si existe un id lo asigna, de lo contrario asigna null
        $this->hora = $args['hora'] ?? ''; // Si existe una hora lo asigna, de lo contrario asigna vacío
        $this->cliente = $args['cliente'] ?? ''; // Si existe un cliente lo asigna, de lo contrario asigna vacío
        $this->email = $args['email'] ?? ''; // Si existe un email lo asigna, de lo contrario asigna vacío
        $this->telefono = $args['telefono'] ?? ''; // Si existe un telefono lo asigna, de lo contrario asigna vacío
        $this->servicio = $args['servicio'] ?? ''; // Si existe un servicio lo asigna, de lo contrario asigna vacío
        $this->precio = $args['precio'] ?? ''; // Si existe un precio lo asigna, de lo contrario asigna vacío
    }

}