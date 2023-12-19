<?php
namespace App;

class Propiedad{

    //base de datos
    //solamente en la clase 
    protected static $db;
    protected static $columnasDB=['id','titulo','precio','imagen','descripcion','habitaciones','wc','estacionamiento','creado','vendedorId'];

    //Errores

    protected static $errores=[];


    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedorId;

    //definir la conexion a la bd
    public static function setDB($database){
        self::$db=$database;
    }

    public function __construct($args=[]){
        $this->id=$args['id'] ?? '';
        $this->titulo=$args['titulo'] ?? '';
        $this->precio=$args['precio'] ?? '';
        $this->imagen=$args['image'] ?? '';
        $this->descripcion=$args['descripcion'] ?? '';
        $this->habitaciones=$args['habitaciones'] ?? '';
        $this->wc=$args['wc'] ?? '';
        $this->estacionamiento=$args['estacionamiento'] ?? '';
        $this->creado=date("Y/m/d")?? '';
        $this->vendedorId=$args['vendedorId'] ?? '';
    }   

    public function atributos(){
        $atributos=[];
        foreach(self::$columnasDB as $columna){
            if($columna==="id") continue;

            $atributos[$columna]=$this->$columna;
        }

        return $atributos;
    }    
    public function sanitizarAtributos():array{
        $atributos=$this->atributos();
        $sanitizado=[];

        foreach($atributos as $key=>$value){
            $sanitizado[$key]=self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    public function setImage($image){
        //asignar al atributo de imagen el nombr4e de la imagen
        if($image){
            $this->imagen=$image;

        }
        
    }

    public function guardar_propiedad(){
    //sanitizar datos evita inyeccion sql
        $atributos=$this->sanitizarAtributos();

        // Insertar en la BD.
        // echo "No hay errores";
        //creamos el query
        $query = "INSERT INTO propiedades ( ";
        $query.=join(', ',array_keys($atributos));
        $query.=" ) VALUES (' "; 
        $query.=join("', '",array_values($atributos));
        $query.=" ') ";

        $resultado=self::$db->query($query);
      
        return $resultado;
    }   


public static function getErrores(){
    return self::$errores;
}


public function validar(){
    
    if (!$this->titulo) {
        self::$errores[] = 'Debes añadir un Titulo';
    }
    if (!$this->precio) {
        self::$errores[] = 'El Precio es Obligatorio';
    }
    if (!$this->descripcion) {
        self::$errores[] = 'La Descripción es obligatoria';
    }
    if (!$this->habitaciones) {
        self::$errores[] = 'La Cantidad de Habitaciones es obligatoria';
    }
    if (!$this->wc) {
        self::$errores[] = 'La cantidad de WC es obligatoria';
    }
    if (!$this->estacionamiento) {
        self::$errores[] = 'La cantidad de lugares de estacionamiento es obligatoria';
    }
    if (!$this->vendedorId) {
        self::$errores[] = 'Elige un vendedor';
    }

    if (!$this->imagen) {
        self::$errores[] = 'Imagen no válida';
    }

    return self::$errores;
}





}






?>