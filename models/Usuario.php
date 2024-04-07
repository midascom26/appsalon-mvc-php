<?php

namespace Model; // Definir un espacio de nombres para la clase

// Heredar la clase ActiveRecord
class Usuario extends ActiveRecord {

    // Base de datos
    protected static $tabla = 'usuarios'; // Nombre de la tabla en la base de datos
    protected static $columnasDB = [
        'id', // campo id de la tabla usuarios
        'nombre', // campo nombre de la tabla usuarios
        'apellido', // campo apellido de la tabla usuarios
        'email', // campo email de la tabla usuarios
        'password', // campo password de la tabla usuarios
        'telefono', // campo telefono de la tabla usuarios
        'admin', // campo admin de la tabla usuarios
        'confirmado', // campo confirmado de la tabla usuarios
        'token' // campo token de la tabla usuarios
    ]; // Columnas de la tabla usuarios de la base de datos

    // Variables
    public $id; // Atributo id de la tabla usuarios
    public $nombre; // Atributo nombre de la tabla usuarios
    public $apellido; // Atributo apellido de la tabla usuarios
    public $email; // Atributo email de la tabla usuarios
    public $password; // Atributo password de la tabla usuarios
    public $telefono; // Atributo telefono de la tabla usuarios
    public $admin; // Atributo admin de la tabla usuarios
    public $confirmado; // Atributo confirmado de la tabla usuarios
    public $token; // Atributo token de la tabla usuarios

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; // Si existe un id lo asigna, de lo contrario asigna null
        $this->nombre = $args['nombre'] ?? ''; // Si existe un nombre lo asigna, de lo contrario asigna vacío
        $this->apellido = $args['apellido'] ?? ''; // Si existe un apellido lo asigna, de lo contrario asigna vacío
        $this->email = $args['email'] ?? ''; // Si existe un email lo asigna, de lo contrario asigna vacío
        $this->password = $args['password'] ?? ''; // Si existe un password lo asigna, de lo contrario asigna vacío
        $this->telefono = $args['telefono'] ?? ''; // Si existe un teléfono lo asigna, de lo contrario asigna vacío
        $this->admin = $args['admin'] ?? 0; // Si existe un admin lo asigna, de lo contrario asigna 0
        $this->confirmado = $args['confirmado'] ?? 0; // Si existe un confirmado lo asigna, de lo contrario asigna 0
        $this->token = $args['token'] ?? ''; // Si existe un token lo asigna, de lo contrario asigna vacío
    }

    //-------------------------validarNuevaCuenta--------------------------------

    // Mensajes de validación de errores para la creación de una cuenta
    // Este método se implementa en la vista auth/crear-cuenta
    public function validarNuevaCuenta() {
        // Validar que el campo nombre no esté vacío
        if (!$this->nombre) {
            // Se utiliza self porque la variable $alertas es una variable estática que se hereda de la clase ActiveRecord
            self::$alertas['error'][] = "El Nombre es Obligatorio";
        }

        // Validar que el campo apellido no esté vacío
        if (!$this->apellido) {
            self::$alertas['error'][] = "El Apellido es Obligatorio";
        }

        // Validar que el campo teléfono no esté vacío
        if (!$this->telefono) {
            self::$alertas['error'][] = "El Teléfono es Obligatorio";
        }

        // Validar que el campo email no esté vacío
        if (!$this->email) {
            self::$alertas['error'][] = "El Email es Obligatorio";
        }

        // Validar que el campo password no esté vacío
        if (!$this->password) {
            self::$alertas['error'][] = "El Password es Obligatorio";
        }

        // Validar la longitud del password
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El Password debe tener al menos 6 caracteres";
        }

        // Se utiliza self porque la variable $alertas es una variable estática que se hereda de la clase ActiveRecord
        return self::$alertas; // Retorna las alertas
    }
    //--------------------------------------------------------------------------

    //-------------------------validarLogin-------------------------------------

    public function validarLogin() {
        // Validar que el campo email no esté vacío
        if (!$this->email) {
            self::$alertas['error'][] = "El Email es Obligatorio";
        }

        // Validar que el campo password no esté vacío
        if (!$this->password) {
            self::$alertas['error'][] = "El Password es Obligatorio";
        }

        // Se utiliza self porque la variable $alertas es una variable estática que se hereda de la clase ActiveRecord
        return self::$alertas; // Retorna las alertas
    }
    //--------------------------------------------------------------------------

    //-------------------------validarEmail-------------------------------------

    public function validarEmail() {
        // Validar que el campo email no esté vacío
        if (!$this->email) {
            self::$alertas['error'][] = "El Email es Obligatorio";
        }

        // Se utiliza self porque la variable $alertas es una variable estática que se hereda de la clase ActiveRecord
        return self::$alertas; // Retorna las alertas
    }
    //--------------------------------------------------------------------------

    //-------------------------validarPassword----------------------------------

    public function validarPassword() {
        // Validar que el campo password no esté vacío
        if (!$this->password) {
            self::$alertas['error'][] = "El Password es Obligatorio"; // Asignamos el mensaje de error
        }

        // Validar la longitud del password
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El Password debe tener al menos 6 caracteres"; // Asignamos el mensaje de error
        }

        // Se utiliza self porque la variable $alertas es una variable estática que se hereda de la clase ActiveRecord
        return self::$alertas; // Retorna las alertas
    }
    //--------------------------------------------------------------------------

    //-------------------------existeUsuario-----------------------------------

    public function existeUsuario() {
        // Definir la consulta a la tabla usuarios de la base de datos para verificar si el email ya existe
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1"; // Consulta SQL
        
        // Se utiliza self porque la variable $db es una variable estática que se hereda de la clase ActiveRecord
        // La variable $db es la conexión a la base de datos y tiene un método query() para consultar la base de datos
        // Al método query() se le pasa la consulta SQL, en este caso se le pasa la variable $query
        // $resultado es un objeto que contiene el resultado de la consulta a la base de datos
        $resultado = self::$db->query($query); // Consultar la base de datos

        // num_rows es una propiedad del objeto $resultado que se utiliza para obtener el número de filas que devuelve la consulta
        if ($resultado->num_rows) {
            // Si el email ya existe en la base de datos
            self::$alertas['error'][] = "El Usuario ya está registrado"; // Mensaje de error
        }

        return $resultado; // Retorna el resultado de la consulta
    }
    //--------------------------------------------------------------------------

    //-------------------------hashPassword-----------------------------------

    public function hashPassword() {
        // Generar un hash del password
        // password_hash() es una función de PHP que se utiliza para generar un hash de una cadena de textO
        // Se le pasa el password y PASSWORD_BCRYPT que es un algoritmo de encriptación
        // El hash generado se asigna a la variable $this->password
        // $this->password es el password que se obtiene del formulario de la vista auth/crear-cuenta
        // Se genera el password encriptado para almacenarlo en la base de datos
        // el passord encriptado contiene 60 caracteres
        $this->password = password_hash($this->password, PASSWORD_BCRYPT); // Generar un hash del password
    }
    //--------------------------------------------------------------------------

    //-------------------------crearToken-----------------------------------

    public function crearToken() {
        // Generar un token único
        // uniqid() es una función de PHP que se utiliza para generar un token único
        // El token generado se asigna a la variable $this->token
        // $this->token es el token que se genera para el usuario
        // El token se utiliza para verificar la cuenta del usuario
        // El token se envía por correo electrónico al usuario para verificar la cuenta
        $this->token = uniqid();
    }
    //--------------------------------------------------------------------------

    //-----------------comprobarPasswordAndVerificado--------------------------

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password); // Verificar el password - retorna true o false
        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = "El Password es incorrecto o tu cuenta no ha sido confirmada"; // Mensaje de error
        } else {
            return true;
        }
    }
}