<?php
use App\Vendedor;
include '../includes/app.php';
// Proteger esta ruta.

estaAutenticado();

//metodo para obtener todas las propiedades utilizando active records
use App\Propiedad;


$propiedades=Propiedad::all();
$vendedores=Vendedor::all();

// debugg($propiedades);
// Validar la URL 
$mensaje = $_GET['mensaje'] ?? null;


// Importar el Template

incluirTemplate('header');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id=$_POST["id_eliminar"];

    if($id){
        $tipo=$_POST['tipo'];
        if(validarTipoContenido($tipo)){
            //compara lo que vamos a eliminar
            if($tipo==='vendedor'){
                $vendedor=Vendedor::find($id);
                $vendedor->eliminar();
            }else if($tipo==='propiedad'){
                $propiedad=Propiedad::find($id);
                $propiedad->eliminar();
            }

        }
    }
    

}
?>

<h1 class="fw-300 centrar-texto">Administración</h1>

<main class="contenedor seccion contenido-centrado">


    <?php
        $m=mostrarNotificacion(intval($mensaje));

        if($m){?>
            <p class="alerta exito"><?php echo s($m)?></p>
        <?php } ?>
    

    <a href="/bienesraices/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
    <a href="/bienesraices/admin/vendedores/crear.php" class="boton boton-amarillo">Nuevo Vendedor</a>
    <h2>Propiedades</h2>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach( $propiedades as $propiedad ): ?>
            <tr>
                <td><?php echo $propiedad->id; ?></td>
                <td><?php echo $propiedad->titulo; ?></td>
                <td>
                    <img src="/bienesraices/imagenes/<?php echo $propiedad->imagen; ?>"" width="100" class="imagen-tabla">
                </td>
                <td>$ <?php echo $propiedad->precio; ?></td>
                <td>
                <form method="POST">
                    <input type="hidden" name="id_eliminar" value="<?php echo $propiedad->id; ?>">
                    <input type="hidden" name="tipo" value="propiedad">
                    <input type="submit" href="/bienesraices/admin/propiedades/borrar.php" class="boton boton-rojo" value="Borrar">
                </form>
                    
                    <a href="/bienesraices/admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton boton-verde">Actualizar</a>
                </td>
            </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Vendedores</h2>


    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach( $vendedores as $vendedor ): ?>
            <tr>
                <td><?php echo $vendedor->id; ?></td>
                <td><?php echo $vendedor->nombre." ".$vendedor->apellido; ?></td>
                <td><?php echo $vendedor->telefono; ?></td>
                <td>
                <form method="POST">
                    <input type="hidden" name="id_eliminar" value="<?php echo $vendedor->id; ?>">
                    <input type="hidden" name="tipo" value="vendedor">
                    <input type="submit" href="/bienesraices/admin/propiedades/borrar.php" class="boton boton-rojo" value="Borrar">
                </form>
                    
                    <a href="/bienesraices/admin/vendedores/actualizar.php?id=<?php echo $vendedor->id; ?>" class="boton boton-verde">Actualizar</a>
                </td>
            </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

</main>

<?php 
    incluirTemplate('footer');
?>