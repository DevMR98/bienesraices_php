<?php
use App\ActiveRecord;
require "funciones.php";
require "config/database.php";
require __DIR__."/../vendor/autoload.php";

//conectar a la base de datos
$db=conectarDb();


//pasamos el objeto db a el metodo estatico de la clase Propiedad
ActiveRecord::setDB($db);

