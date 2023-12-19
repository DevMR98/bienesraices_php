<?php

require '../../includes/app.php';
use App\Propiedad;
use Intervention\Image\ImageManagerStatic as Image;

// Proteger esta ruta.

estaAutenticado();

$db = conectarDb();

$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

// Validar 

$errores = Propiedad::getErrores();

$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedor = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //crear una nueva instancia
    $propiedad = new Propiedad($_POST);

    $imagePath =md5(uniqid(rand(), true)).".jpg";
    //setear la image
    //Realiza un resize a la imagen con intervetion
    if ($_FILES['image']['tmp_name']) {
        $image = Image::make($_FILES['image']['tmp_name'])->fit(800, 600);
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
        <fieldset>
            <legend>Información General</legend>
            <label for="titulo">Titulo:</label>
            <input name="titulo" type="text" id="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio: </label>
            <input name="precio" type="number" id="precio" placeholder="Precio" value="<?php echo $precio; ?>">

            <label for="imagen">Imagen: </label>
            <input name="image" type="file" id="image" enctype="multipart/form-data">


            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion"><?php echo $descripcion; ?></textarea>

        </fieldset>


        <fieldset>
            <legend>Información Propiedad</legend>

            <label for="habitaciones">Habitaciones:</label>
            <input name="habitaciones" type="number" min="1" max="10" step="1" id="habitaciones"
                value="<?php echo $habitaciones; ?>">

            <label for="wc">Baños:</label>
            <input name="wc" type="number" min="1" max="10" step="1" id="wc" value="<?php echo $wc; ?>">

            <label for="estacionamiento">Estacionamiento:</label>
            <input name="estacionamiento" type="number" min="1" max="10" step="1" id="estacionamiento"
                value="<?php echo $estacionamiento; ?>">

            <legend>Información Vendedor:</legend>
            <label for="nombre_vendedor">Nombre:</label>

            <select name="vendedorId" id="nombre_vendedor">
                <option selected value="">-- Seleccione --</option>
                <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                    <option <?php echo $vendedor === $row['id'] ? 'selected' : '' ?> value="<?php echo $row['id']; ?>">
                        <?php echo $row['nombre'] . " " . $row['apellido']; ?>
                    <?php endwhile; ?>
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">

    </form>

</main>


<?php

incluirTemplate('footer');

mysqli_close($db); ?>

</html>