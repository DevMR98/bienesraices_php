<?php
use App\Propiedad;
use Intervention\Image\ImageManagerStatic as Image;
include '../../includes/app.php';
estaAutenticado();


// Verificar el id
$id =  $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);
if(!$id) {
    header('Location: /bienesraices/admin');
}


// Obtener la propiedad
$propiedad=Propiedad::find($id);


// obtener vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

$errores = Propiedad::getErrores();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $args=[];
    $args=$_POST["propiedad"];

    $propiedad->sincronizar($args);
   
    $errores=$propiedad->validar();

    $imagePath =md5(uniqid(rand(), true)).".jpg";
    //subido de archivos
    if ($_FILES['propiedad']['tmp_name']['image']) {
        $image = Image::make($_FILES['propiedad']['tmp_name']['image'])->fit(800, 600);
        $propiedad->setImage($imagePath);
    }
    if (empty($errores)) {
        //almacenar imagen
        $image->save(CARPETA_IMAGENES.$imagePath);
        // Si hay una imagen NUEVA, entonces borrar la anterior.
        $propiedad->guardar_propiedad();
    }
}
?>

<?php
$nombrePagina = 'Crear Propiedad';
incluirTemplate('header');
?>

<h1 class="fw-300 centrar-texto">Administración - Editar Propiedad</h1>

<main class="contenedor seccion contenido-centrado">
    <a href="/bienesraices/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php 
            include "../../includes/templates/formulario_propiedades.php";
        ?>

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">

    </form>

</main>


<?php

incluirTemplate('footer');

mysqli_close($db); ?>

</html>