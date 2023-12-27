<?php
require "../../includes/app.php";

use App\Propiedad;
use App\Vendedor;

estaAutenticado();

$errores=Vendedor::getErrores();

if($_SERVER['REQUEST_METHOD']==="POST"){
    // debugg($_POST);
    

    $vendedor=new Vendedor($_POST['vendedor']);

    $errores=$vendedor->validar();

    if(empty($errores)){
        $resultado=$vendedor->guardar_propiedad();
    }

    if($resultado){
        header("location:/bienesraices/admin/index.php?mensaje=1");

    }
    
}

incluirTemplate('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1>Registrar Vendedores</h1>
    <a href="/bienesraices/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST">
        <?php 
        include "../../includes/templates/formulario_vendedores.php"
        ?>

        <input type="submit" value="Registrar Vendedor" class="boton boton-verde">

    </form>

</main>