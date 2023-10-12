<?php
// Función para obtener la lista de archivos y carpetas en un directorio
function obtenerListaArchivos($directorio, $carpetaActual = '')
{
  $rutaActual = $directorio . '/' . $carpetaActual;
  $archivos = scandir($rutaActual);
  $listaArchivos = [];

  foreach ($archivos as $archivo) {
    if ($archivo === '.' || $archivo === '..') {
      continue;
    }

    $rutaArchivo = $rutaActual . '/' . $archivo;
    $esCarpeta = is_dir($rutaArchivo);

    // Obtener la extensión del archivo
    $extension = pathinfo($archivo, PATHINFO_EXTENSION);

    // Agregar solo archivos .txt y carpetas a la lista
    if ($esCarpeta || $extension === 'txt') {
      $listaArchivos[] = [
        'nombre' => $archivo,
        'ruta' => $rutaArchivo,
        'esCarpeta' => $esCarpeta
      ];
    }
  }

  return $listaArchivos;
}

// Obtener el directorio actual
$directorioActual = __DIR__;

// Obtener la carpeta actual (si se especifica en la URL)
if (isset($_GET['carpeta'])) {
  $carpetaActual = $_GET['carpeta'];
} else {
  $carpetaActual = '';
}

// Procesar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = $_POST['titulo'];
  $contenido = $_POST['contenido'];
  $carpeta = $_POST['carpeta'];

  // Reemplazar espacios en el título y agregar extensión .txt
  $nombreArchivo = str_replace(' ', '_', $titulo) . '.txt';

  // Obtener la ruta completa de la carpeta
  $rutaCarpeta = $directorioActual . '/' . $carpeta;

  // Verificar si la carpeta existe y, si no, crearla
  if (!is_dir($rutaCarpeta)) {
    mkdir($rutaCarpeta, 0777, true);
  }

  // Crear la ruta completa del archivo dentro de la carpeta
  $rutaArchivo = $rutaCarpeta . '/' . $nombreArchivo;

  // Escribir el contenido en el archivo
  file_put_contents($rutaArchivo, $contenido);

  // Redireccionar a la página principal con un parámetro de éxito
  header('Location: index.php?success=true');
  exit();
}

// Eliminar archivo o carpeta si se recibe el parámetro "eliminar"
if (isset($_GET['eliminar'])) {
  $elementoEliminar = $_GET['eliminar'];
  $rutaEliminar = $directorioActual . '/' . $elementoEliminar;

  $extensionesRestringidas = ['.php', '.css', '.js'];
  $extensionArchivo = strtolower(pathinfo($rutaEliminar, PATHINFO_EXTENSION));

  if (in_array($extensionArchivo, $extensionesRestringidas)) {
    // Archivo con extensión restringida, redireccionar a la página principal sin eliminar
    header('Location: index.php');
    exit();
  }

  // Eliminar archivo o carpeta si no tiene una extensión restringida
  if (is_file($rutaEliminar) && !in_array($extensionArchivo, $extensionesRestringidas)) {
    // Es un archivo y no tiene una extensión restringida
    unlink($rutaEliminar);
  } elseif (is_dir($rutaEliminar)) {
    // Es una carpeta
    eliminarCarpeta($rutaEliminar);
  }

  // Redireccionar a la página principal después de eliminar
  header('Location: index.php');
  exit();
}

// Función para eliminar una carpeta y su contenido de manera recursiva
function eliminarCarpeta($carpeta)
{
  if (!is_dir($carpeta)) {
    return;
  }

  $archivos = scandir($carpeta);
  foreach ($archivos as $archivo) {
    if ($archivo === '.' || $archivo === '..') {
      continue;
    }

    $rutaArchivo = $carpeta . '/' . $archivo;

    if (is_file($rutaArchivo)) {
      unlink($rutaArchivo);
    } elseif (is_dir($rutaArchivo)) {
      eliminarCarpeta($rutaArchivo);
    }
  }

  rmdir($carpeta);
}

// Obtener la lista de archivos y carpetas en el directorio actual y carpeta actual
$listaArchivos = obtenerListaArchivos($directorioActual, $carpetaActual);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <title>Document</title>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

  <!-- <div class="container mt-4">
    <h1>Bloc de Notas</h1> -->

  <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
    <div class="alert alert-success" role="alert">
      ¡Su nota se Guardo con exito!
    </div>
  <?php endif; ?>

  <div class="page">
    <div class="login">Crear una nueva nota</div>
    <div class="container">
      <div class="left">
        <!-- <h2 class="mt-4">Crear una nueva nota</h2> -->
        <!-- <div class="eula">By logging in you agree to the ridiculously long terms that you didn't bother to read</div> -->

        <form method="POST">
          <div class="mb-3 mx-2">
            <label for="titulo" class="form-label mt-2">Título:</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
          </div>
          <div class="mb-3 mx-2">
            <label for="contenido" class="form-label">Contenido:</label>
            <textarea id="contenido" name="contenido" required class="form-control"></textarea>
          </div>
          <div class="mb-3 mx-2">
            <div class="collapse" id="collapseExample">
              <label for="carpeta" class="form-label">Carpeta:</label>
              <input type="text" class="form-control" id="carpeta" name="carpeta"
                placeholder="Nombre de la carpeta (opcional)">
            </div>
          </div>
          <button class="btn btn-primary mx-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
            aria-expanded="false" aria-controls="collapseExample">
            <img width="40px" src="folder.png" alt="Forder">
          </button>
          <button type="submit" class="btn btn-success">Guardar</button>
        </form>
      </div>
      <div class="right">
        <!-- <svg viewBox="0 0 320 300">
            <defs>
              <linearGradient inkscape:collect="always" id="linearGradient" x1="13" y1="193.49992" x2="307"
                y2="193.49992" gradientUnits="userSpaceOnUse">
                <stop style="stop-color:#ff00ff;" offset="0" id="stop876" />
                <stop style="stop-color:#ff0000;" offset="1" id="stop878" />
              </linearGradient>
            </defs>
            <path
              d="m 40,120.00016 239.99984,-3.2e-4 c 0,0 24.99263,0.79932 25.00016,35.00016 0.008,34.20084 -25.00016,35 -25.00016,35 h -239.99984 c 0,-0.0205 -25,4.01348 -25,38.5 0,34.48652 25,38.5 25,38.5 h 215 c 0,0 20,-0.99604 20,-25 0,-24.00396 -20,-25 -20,-25 h -190 c 0,0 -20,1.71033 -20,25 0,24.00396 20,25 20,25 h 168.57143" />
          </svg> -->

        <div class="login">Archivos y carpetas:</div>
        <?php if (count($listaArchivos) > 0): ?>

          <ul class="list-group mx-3">
            <?php if ($carpetaActual !== ''): ?>
              <li class="list-group-item list-group-item-dark">
                <a href="?carpeta="><button type="button" class="btn btn-secondary">Regresar</button></a>
              </li>
            <?php endif; ?>
            <?php foreach ($listaArchivos as $archivo): ?>
              <li class="list-group-item">
                <?php if ($archivo['esCarpeta']): ?>
                  <img src="Imagen.png" alt="carpeta" width="27" height="25">
                  <a class="letras" href="?carpeta=<?= $carpetaActual . '/' . $archivo['nombre'] ?>">
                    <?= $archivo['nombre'] ?>
                  </a>
                  <a href="?eliminar=<?= $carpetaActual . '/' . $archivo['nombre'] ?>" class="text-danger ms-2"
                    onclick="return confirm('¿Estás seguro/a de que quieres eliminar esta carpeta?')"><button type="button"
                      class="btn btn-danger">Eliminar</button></a>
                <?php else: ?>
                  <img src="file.png" alt="archivo" width="27" height="27">
                  <?= $archivo['nombre'] ?>
                  <a href="?eliminar=<?= $carpetaActual . '/' . $archivo['nombre'] ?>" class="text-danger ms-2"
                    onclick="return confirm('¿Estás seguro/a de que quieres eliminar este archivo?')"><button type="button"
                      class="btn btn-danger">Eliminar</button></a>
                  <a href="ver_archivo.php?ruta=<?= $archivo['ruta'] ?>" class="text-success ms-2">
                    <button type="button" class="btn btn-primary">Ver</button></a>
                  <a href="editar_archivo.php?ruta=<?= $archivo['ruta'] ?>" class="text-success ms-2">
                    <button type="button" class="btn btn-warning">Editar</button></a>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <?php if ($carpetaActual !== ''): ?>
            <ul class="list-group mx-3">
              <li class="list-group-item list-group-item-dark">
                <a href="?carpeta="><button type="button" class="btn btn-secondary">Regresar</button></a>
              </li>
            </ul>
          <?php endif; ?>
          <div class="card card-body text-bg-dark mt-2 mx-3">
            No hay archivos ni carpetas en este directorio.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>


  </div>


</body>

</html>