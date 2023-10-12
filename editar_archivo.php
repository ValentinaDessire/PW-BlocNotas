<?php
if (isset($_GET['ruta'])) {
  $rutaArchivo = $_GET['ruta'];

  // Verificar si el archivo existe
  if (file_exists($rutaArchivo)) {
    // Obtener el título del archivo
    $titulo = pathinfo($rutaArchivo, PATHINFO_FILENAME);

    // Leer el contenido del archivo
    $contenido = file_get_contents($rutaArchivo);

    // Procesar el envío del formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nuevoContenido = $_POST['nuevo_contenido'];

      // Escribir el nuevo contenido en el archivo
      file_put_contents($rutaArchivo, $nuevoContenido);

      // Redireccionar a la página principal con un parámetro de éxito
      header('Location: index.php?success=true');
      exit();
    }
  } else {
    // El archivo no existe, redireccionar a la página principal
    header('Location: index.php');
    exit();
  }
} else {
  // No se proporcionó la ruta del archivo, redireccionar a la página principal
  header('Location: index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <title>Editar Archivo -
    <?= $titulo ?>
  </title>
  <style>
    body{
      background-color: #e9ecef;
    }
    .contenedor {
      width: 80%;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    textarea {
      height: 150px;
    }
  </style>
</head>

<body>
  <!-- <div class="container mt-4">
    <h1>Editar Archivo -
    </h1>

    <h2 class="mt-4">Contenido actual:</h2>
    <pre></pre>

    <h2 class="mt-4">Editar contenido:</h2>
    <form method="POST">
      <div class="mb-3">
        <label for="nuevo_contenido" class="form-label">Nuevo Contenido:</label>
        <textarea id="nuevo_contenido" name="nuevo_contenido" required class="form-control" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
  </div> -->

  <div class="contenedor">
    <div class="row">
      <div class="col-xl-6 mb-3 mb-xl-0">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <b>
                <?= $titulo ?>
              </b>
            </h5>
            <p class="card-text">
              <label for="nuevo_contenido" class="form-label"><b> Contenido Actual: </b></label>
              <br>
              <?= $contenido ?>
            </p>
          </div>
        </div>
      </div>
      <div class="col-xl-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <b>
                <?= $titulo ?>
              </b>
            </h5>
            <form method="POST">
              <div class="mb-3">
                <label for="nuevo_contenido" class="form-label"><b> Nuevo Contenido: </b></label>
                <textarea id="nuevo_contenido" name="nuevo_contenido" required class="form-control" rows="3"></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Guardar</button>
              <a href="index.php"><button type="button" class="btn btn-secondary">Volver</button></a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>