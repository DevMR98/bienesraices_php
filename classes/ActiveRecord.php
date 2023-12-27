<?php

namespace App;
// error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// ini_set('display_errors', 'Off');
class ActiveRecord{

        //base de datos
    //solamente en la clase 
    protected static $db;
    protected static $columnasDB = [""];
    protected static $tabla="";
    //Errores

    protected static $errores = [];



    //definir la conexion a la bd
    public static function setDB($database)
    {
        self::$db = $database;
    }

    

    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === "id")
                continue;

            $atributos[$columna] = $this->$columna;
        }

        return $atributos;
    }
    public function sanitizarAtributos(): array
    {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    public function setImage($image)
    {
        //elimina la imagen previa
        if(!is_null($this->id)){
            $this->borrarImagen();
        }
        //asignar al atributo de imagen el nombr4e de la imagen
        if ($image) {
            $this->imagen = $image;
        }

    }

    public function borrarImagen(){
        //comprobar que existe archivo
        $existeArchivo=file_exists(CARPETA_IMAGENES.$this->imagen);

        if($existeArchivo){
            unlink(CARPETA_IMAGENES.$this->imagen);
        }
    }

    public function guardar_propiedad(){
        if(!is_null($this->id)){
            return $this->actualizando();
        }else{
            return $this->crear_propiedad();
        }
    }
    public function crear_propiedad()
    {
        //sanitizar datos evita inyeccion sql
        $atributos = $this->sanitizarAtributos();

        // Insertar en la BD.
        // echo "No hay errores";
        //creamos el query
        $query = "INSERT INTO ".static::$tabla." ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        $resultado = self::$db->query($query);

        return $resultado;
    }

    public function actualizando(){
         //sanitizar datos evita inyeccion sql
         $atributos = $this->sanitizarAtributos();

         foreach($atributos as $key => $value){
            $valores[]="{$key}='{$value}'";
         }

         $query = "UPDATE ".static::$tabla." SET ";
         $query.= join(', ',$valores);
         $query.= " WHERE id = '".self::$db->escape_string($this->id)."'";
         $query.= " LIMIT 1";

         $resultado=self::$db->query($query);

         if ($resultado) {
            header('location: /bienesraices/admin/index.php?mensaje=2');
        }
    }

    public function eliminar(){
        $query="DELETE FROM ".static::$tabla." WHERE id=".self::$db->escape_string($this->id)." LIMIT 1 ";
        $resultado=self::$db->query($query);

        if ($resultado) {
            $this->borrarImagen();
            header('location: /bienesraices/admin?mensaje=3');
        }
    }

    public static function getErrores()
    {

        return static::$errores;
    }


    public function validar()
    {
        static::$errores=[];
        return static::$errores;
    }



    //lista todas las propiedades

    public static function all()
    {
        $query = "SELECT *FROM ".static::$tabla;
        $resultado = self::consultar_sql($query);


        // Verificar si se encontraron propiedades
        if (!empty($resultado)) {
            return $resultado;
        } else {
            // Manejar el caso en que no se encuentran propiedades
            return null; // o puedes devolver un valor predeterminado según tu lógica
        }

    }

    //obtiene determinado numero de registros
    public static function get($cantidad)
    {
        $query = "SELECT *FROM ".static::$tabla." LIMIT ".$cantidad;
        $resultado = self::consultar_sql($query);


        // Verificar si se encontraron propiedades
        if (!empty($resultado)) {
            return $resultado;
        } else {
            // Manejar el caso en que no se encuentran propiedades
            return null; // o puedes devolver un valor predeterminado según tu lógica
        }

    }



    //buscar un registro por id
    public static function find($id)
    {
        $query = "SELECT * FROM " .static::$tabla." WHERE id = ${id}";

        $resultado = self::consultar_sql($query);

        // Verificar si se encontró la propiedad
        if (!empty($resultado)) {
            return array_shift($resultado);
        } else {
            // Manejar el caso en que no se encuentra la propiedad
            return null; // o puedes devolver un valor predeterminado según tu lógica
        }

    }

    public static function consultar_sql($query)
    {
        //consultar base de datos
        $resultado = self::$db->query($query);
        //iterar los rsultados
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);

        }
        //liberar memoria
        $resultado->free();
        //retornar los resultados
        return $array;
    }


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
    
    //sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args=[]){
        foreach($args as $key=>$value){
            if(property_exists($this,$key ) && !is_null($value)){
                $this->$key=$value;

            }

        }
    }



}




?>