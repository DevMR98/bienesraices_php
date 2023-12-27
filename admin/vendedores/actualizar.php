<?php
require "../../includes/app.php";

use App\Propiedad;
use App\Vendedor;

estaAutenticado();

//validar que sea un id valido
$id=$_GET['id'];
// $id=filter_var($id,FILTER_VALIDATE_INT);

if(!$id){
    header("location:/bienesraices/admin");
}

//obtener elarreglo del vendedor
$vendedor=Vendedor::find($id);



$errores=Propiedad::getErrores();

if($_SERVER['REQUEST_METHOD']==="POST"){
    
    //asignar los valores
    $args=$_POST["vendedor"];
    // debugg($args);

    //sincronizar el objeto en memeoria con lo que el usuario escribió
    $vendedor->sincronizar($args);
    // debugg($vendedor);
    //validacion
    $errores=$vendedor->validar();

    if(empty($errores)){
        $vendedor->guardar_propiedad();
    }    

}

incluirTemplate('header');

?>

<main class="contenedor seccion contenido-centrado">
    <h1>Actualizar Vendedor</h1>
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

        <input type="submit" value="Guardar Cambios" class="boton boton-verde">

    </form>

</main>