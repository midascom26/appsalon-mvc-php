<?php

/*
IMPORTANTE: Para este proyecto vamos a utilizar self para
lo que tiene que ver con la Base de Datos, por ejemplo: self::$db
*/

namespace Model; // Definir un espacio de nombres para la clase

class ActiveRecord
{
    // Atributo para la conexión a la BD- sólo se puede acceder unicamente desde esta clase
    protected static $db; // los objetos van a compartir la misma conexión a la BD
    protected static $tabla = ''; // Columnas de la tabla que generemos en la BD
    protected static $columnasDB = []; // Se asigna el nombre de la tabla que generemos en la BD - se utiliza en las clases hijas

    // Validacion - Alertas y Mensajes de errores
    protected static $alertas = []; // Areglo para almacenar las alertas y mensajes de errores

    //-----------------------------setDB------------------------------------

    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database)
    {
        // self hace referencia a los atributos estáticos
        // Se utiliza self y no static, porque todas las clases se van a conectar a la misma BD
        self::$db = $database; // Utilizamos self para interactuar con la base de datos
    }
    //----------------------------------------------------------------------

    //-----------------------------setAlerta--------------------------------
    // Alertas y Mensajes de errores
    public static function setAlerta($tipo, $mensaje)
    {
        static::$alertas[$tipo][] = $mensaje; // Se utiliza static porque se va a utilizar en las clases hijas
    }
    //----------------------------------------------------------------------

    //-----------------------------getAlertas-------------------------------
    // Validación
    public static function getAlertas()
    {
        return static::$alertas; // Retorna las alertas y mensajes de errores, se utiliza static porque se va a utilizar en las clases hijas
    }
    //----------------------------------------------------------------------

    //-----------------------------validar----------------------------------
    // Validar los datos
    public function validar()
    {
        static::$alertas = []; // Inicializar el arreglo de alertas, se utiliza static porque se va a utilizar en las clases hijas
        return static::$alertas; // Retorna las alertas y mensajes de errores
    }
    //----------------------------------------------------------------------

    //-----------------------------consultarSQL-----------------------------
    // Consulta SQL para crear un objeto en Memoria
    public static function consultarSQL($query)
    {
        // Consultar la base de datos
        $resultado = self::$db->query($query); // Ejecutar la consulta

        // Iterar los resultados
        $array = [];
        while ($registro = $resultado->fetch_assoc()) { // fetch_assoc() - Devuelve un array asociativo de strings que representa a la fila obtenida o NULL si no hay más filas en el conjunto de resultados.
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    }
    //----------------------------------------------------------------------

    //-----------------------------crearObjeto------------------------------
    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }
    //----------------------------------------------------------------------

    //-----------------------------atributos--------------------------------
    // Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }
    //----------------------------------------------------------------------

    //-----------------------------sanitizarAtributos-----------------------
    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
    //----------------------------------------------------------------------

    //-----------------------------sincronizar-------------------------------
    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
    //----------------------------------------------------------------------

    //-----------------------------guardar-----------------------------------
    // Método guardar registro
    public function guardar()
    {
        $resultado = ''; // Inicializar la variable resultado

        // Si el id no es null, entonces se actualiza
        if (!is_null($this->id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado; // Retornar el resultado
    }
    //----------------------------------------------------------------------

    //-----------------------------all--------------------------------------
    // El total de los registros
    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    //----------------------------------------------------------------------

    //-----------------------------find-------------------------------------
    // Busca un registro por su id
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla  . " WHERE id = $id";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado); // array_shift() - Extrae el primer elemento de un array y lo devuelve - retorna un solo registro
    }
    //----------------------------------------------------------------------

    //-----------------------------get--------------------------------------
    // Obtener Registros con cierta cantidad
    public static function get($limite)
    {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT $limite";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado); // array_shift() - Extrae el primer elemento de un array y lo devuelve - retorna un solo registro
    }
    //----------------------------------------------------------------------

    //-----------------------------where------------------------------------
    // Busca un registro por una columna específica y su valor
    public static function where($columna, $valor)
    {
        $query = "SELECT * FROM " . static::$tabla  . " WHERE $columna = '$valor'";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado); // array_shift() - Extrae el primer elemento de un array y lo devuelve - retorna un solo registro
    }
    //----------------------------------------------------------------------

    //-----------------------------SQL--------------------------------------
    // Consulta plana de SQL (utilizar cuando los métodos del modelo ActiveRecord no sean suficientes)
    // Esta consulta se utiliza porque se debe consultar varias tablas
    public static function SQL($query)
    {
        $resultado = self::consultarSQL($query);
        return $resultado; // Retorna el resultado de la consulta
    }
    //----------------------------------------------------------------------
    
    //-----------------------------crear-------------------------------------
    // crea un nuevo registro
    public function crear()
    {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = "INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES ('";
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        // MUY IMPORTANTE PARA DEBUGUEAR LAS PETICIONES QUE SE ESTÁN ENVIANDO
        // return json_encode(['query' => $query]); // Retorna la consulta SQL, SE PUEDE REVISAR EN POSTMAN Y EN RED EN FETCH EN CHROME

        // Resultado de la consulta
        $resultado = self::$db->query($query); // Ejecutar la consulta, retorna true o false
        return [
            'resultado' =>  $resultado, // Retorna el resultado de la consulta
            'id' => self::$db->insert_id // Retorna el id del registro creado
        ]; // Retorna un array con el resultado y el id del registro creado
    }
    //----------------------------------------------------------------------

    //-----------------------------actualizar--------------------------------
    // Actualizar el registro
    public function actualizar()
    {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        // Consulta SQL
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .=  join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        // Actualizar BD
        $resultado = self::$db->query($query); // Ejecutar la consulta y retorna true o false
        return $resultado;
    }
    //----------------------------------------------------------------------

    //-----------------------------eliminar---------------------------------
    // Eliminar un Registro por su ID
    public function eliminar()
    {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query); // Ejecutar la consulta y retorna true o false
        return $resultado;
    }
    //----------------------------------------------------------------------
}
