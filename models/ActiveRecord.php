<?php

namespace Model;

use Exception;

class ActiveRecord
{

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];

    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database)
    {

        self::$db = $database;
    }
    public static function getDB()
    {
        return self::$db;
    }

    // Setear un tipo de Alerta
    public static function setAlerta($tipo, $mensaje)
    {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Obtener las alertas
    public static function getAlertas()
    {
        return static::$alertas;
    }

    // Validación que se hereda en modelos
    public function validar()
    {
        static::$alertas = [];
        return static::$alertas;
    }

    // Consulta SQL para crear un objeto en Memoria (Active Record)
    public static function consultarSQL($query)
    {
        // Consultar la base de datos

        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    }

    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro)
    {

        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {

                $objeto->$key = $value;
            } else {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

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

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos()
    {

        $atributos = $this->atributos();

        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            if ($value === null) {
                $sanitizado[$key] = null;
            } else {
                $sanitizado[$key] = self::$db->escape_string($value);
            }
        }

        return $sanitizado;
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    // Registros - CRUD
    public function guardar()
    {
        $resultado = '';
        if (!is_null($this->id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    // Obtener todos los Registros
    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    public static function toDoJoin($tablaPrimaria, $columnaPrimaria, $columnaForanea, $columna, $valor)
    {
        $query = "SELECT * FROM " . static::$tabla . " inner join $tablaPrimaria ON " . static::$tabla . ".$columnaForanea  = $tablaPrimaria." . $columnaPrimaria . " WHERE $columna = $valor  ORDER BY " . static::$tabla . ".id DESC";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla  . " WHERE id = ${id}";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite)
    {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT $limite";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE ${columna} = '${valor}'";
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }
    //lo mismo que ere pero todos los resultados
    public static function whereAll($columna, $valor, $ordenarPor = null, $direccion = 'DESC')
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE $columna = '$valor'";

        if ($ordenarPor) {
            $query .= " ORDER BY $ordenarPor $direccion";
        }

        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    //busqueda where con multiples opciones
    public static function whereArray($array = [])
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE ";
        foreach ($array as $key => $value) {

            if ($key == array_key_last($array)) {
                $query .= " $key = '$value' ORDER BY id DESC";
            } else {
                $query .= " $key = '$value' AND";
            }
        }

        $resultado = self::consultarSQL($query);
        return  $resultado;
    }
    public static function whereArrayJoin($array = [], $tablaPrimaria, $columnaPrimaria, $columnaForanea)
    {
        $query = "SELECT * FROM " . static::$tabla . " inner join $tablaPrimaria ON " . static::$tabla . ".$columnaForanea  = $tablaPrimaria." . $columnaPrimaria . " WHERE ";
        foreach ($array as $key => $value) {

            if ($key == array_key_last($array)) {
                $query .= " $key = '$value'";
            } else {
                $query .= " $key = '$value' AND";
            }
        }

        $resultado = self::consultarSQL($query);

        return  $resultado;
    }




    // crea un nuevo registro
    public function crear()
    {
        // Sanitizar los datos

        $atributos = $this->sanitizarAtributos();


        // Insertar en la base de datos
        $query = "INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (";
        // $query .= join("', '", array_values($atributos));
        // $query .= "')";
        foreach ($atributos as $valor) {
            if ($valor === null) {
                // Si el valor es NULL, agrega 'NULL' a la consulta
                $query .= 'NULL, ';
            } else {
                // De lo contrario, agrega el valor entre comillas simples
                $query .= "'" . $valor . "', ";
            }
        }


        $query = rtrim($query, ', ') . ")";



        $resultado = self::$db->query($query);
        return [
            'resultado' =>  $resultado,
            'id' => self::$db->insert_id
        ];
    }

    // Actualizar el registro
    public function actualizar()
    {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        // echo "<pre>";
        // var_dump($atributos);
        // echo "</pre>";

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach ($atributos as $key => $value) {
            if ($value === null) {
                $valores[] = "{$key}=NULL";
            } else {
                $valores[] = "{$key}='{$value}'";
            }
        }
        // echo "<pre>";
        // var_dump($valores);
        // echo "</pre>";

        // Consulta SQL
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .=  join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";





        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Eliminar un Registro por su ID
    public function eliminar()
    {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";

        try {
            self::$db->query($query);
            $resultado = ['status' => true];
            return $resultado;
        } catch (Exception $e) {

            $resultado = ['status' => false, 'code' => $e->getCode()];
            return $resultado;
        }
    }
    public function eliminarWhere($columna, $valor)
    {
        $query = "DELETE FROM "  . static::$tabla . " WHERE $columna = $valor";
        $resultado = self::$db->query($query);
        return $resultado;
    }

    /* suma todos los registros de una columna */
    public static function total($string, $columna_fecha = null, $fecha = null)
    {

        $query = "SELECT sum($string) AS total FROM "  . static::$tabla;
        if ($fecha) {
            $query = "SELECT sum($string) AS total FROM "  . static::$tabla . " WHERE $columna_fecha >=  '$fecha'";
        }

        $resultado = self::$db->query($query);
        return $resultado->fetch_assoc();
    }

    //contar la cantidad de datos dada una condicion 
    public static function contar($columna = null, $valor = null)
    {

        $query = "SELECT count(*) AS total FROM "  . static::$tabla;
        if ($columna) {
            $query = "SELECT count(*) AS total FROM "  . static::$tabla . " WHERE $columna =  $valor";
        }

        $resultado = self::$db->query($query);
        return $resultado->fetch_assoc();
    }
    public static function contarPorFecha($columna = null, $valor = null, $columna_fecha, $fecha)
    {

        $query = "SELECT count(*) AS total FROM "  . static::$tabla . " WHERE $columna_fecha >= '$fecha'";
        if ($columna) {
            $query = "SELECT count(*) AS total FROM "  . static::$tabla . " WHERE $columna =  $valor AND  $columna_fecha >= '$fecha'";
        }



        $resultado = self::$db->query($query);
        return $resultado->fetch_assoc();
    }

    /* multica dos columnas y se trae toda la suma */
    // public static function totalProducto($columna1, $columna2){
    //     $query = "SELECT sum($columna1*$columna2) AS total FROM "  . static::$tabla;
    //     $resultado = self::$db->query($query);
    //     return $resultado->fetch_assoc();
    // }
}
