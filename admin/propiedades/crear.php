<?php

require '../../includes/app.php';
use App\Propiedad;
use Intervention\Image\ImageManagerStatic as Image;

// Proteger esta ruta.

estaAutenticado();

$db = conectarDb();


$propiedad=new Propiedad();
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

// Validar 

$errores = Propiedad::getErrores();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //crear una nueva instancia
    $propiedad = new Propiedad($_POST['propiedad']);

    $imagePath =md5(uniqid(rand(), true)).".jpg";
    //setear la image
    //Realiza un resize a la imagen con intervetion
    
    if ($_FILES['propiedad']['tmp_name']['image']) {
        $image = Image::make($_FILES['propiedad']['tmp_name']['image'])->fit(800, 600);
        $propiedad->setImage($imagePath);
    }

    $errores=$propiedad->validar();
    // El array de errores esta vacio
    if (empty($errores)) {
        //crear carpeta para subir imagenes
        if(!is_dir(CARPETA_IMAGENES)){
            mkdir(CARPETA_IMAGENES);
        }
        //guarda la imagen en el servidor
        $image->save(CARPETA_IMAGENES.$imagePath);

        //guardar en la base de datos
        $resultado=$propiedad->guardar_propiedad();
        if ($resultado) {
            header('location: /bienesraices/admin/index.php?mensaje=1');
        }
    }

}

?>

<?php
$nombrePagina = 'Crear Propiedad';

incluirTemplate('header');
?>

<h1 class="fw-300 centrar-texto">Administración - Nueva Propiedad</h1>

<main class="contenedor seccion contenido-centrado">
    <a href="/bienesraices/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php 
        include "../../includes/templates/formulario_propiedades.php"
        ?>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">

    </form>

</main>


<?php

incluirTemplate('footer');

mysqli_close($db); ?>

</html>