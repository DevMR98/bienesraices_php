<?php
namespace App;

class Propiedad{

    //base de datos
    //solamente en la clase 
    protected static $db;
    protected static $columnasDB=['id','titulo','precio','imagen','descripcion','habitaciones','wc','estacionamiento','creado','vendedorId'];


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
        $this->imagen=$args['imagen'] ?? 'imagen.jpg';
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

    public function guardar_propiedad(){
    //sanitizar datos evita inyeccion sql
        $atributos=$this->sanitizarAtributos();



        // Insertar en la BD.
        // echo "No hay errores";
        //creamos el query
        $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, vendedores_id, creado  ) VALUES ( '$this->titulo', '$this->precio', '$this->imagen', '$this->descripcion',  '$this->habitaciones', '$this->wc', '$this->estacionamiento', '$this->vendedorId', '$this->creado' )";
        
         $stmt=self::$db->query($query);
         debugg($stmt);


    }

    

    
        
}



?>