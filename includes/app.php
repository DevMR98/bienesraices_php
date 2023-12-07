<?php
require "funciones.php";
require "config/database.php";
require __DIR__."/../vendor/autoload.php";

//conectar a la base de datos
$db=conectarDb();

use App\Propiedad;

//pasamos el objeto db a el metodo estatico de la clase Propiedad
Propiedad::setDB($db);

