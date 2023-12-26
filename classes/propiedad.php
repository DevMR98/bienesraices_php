<?php
namespace App;

class Propiedad extends ActiveRecord
{

    protected static $tabla="propiedades";
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];


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

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['image'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date("Y/m/d") ?? '';
        $this->vendedorId = $args['vendedorId'] ?? '';
    }


    public function validar()
    {

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