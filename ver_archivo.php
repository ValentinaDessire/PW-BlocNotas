<?php
if (isset($_GET['ruta'])) {
  $rutaArchivo = $_GET['ruta'];

  // Verificar si el archivo existe
  if (file_exists($rutaArchivo)) {
    // Obtener el título del archivo
    $titulo = pathinfo($rutaArchivo, PATHINFO_FILENAME);

    // Leer el contenido del archivo
    $contenido = file_get_contents($rutaArchivo);
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
  <title>
    <?= $titulo ?>
  </title>
  <style>
    body{
      background-color: #e9ecef;
    }
    .card {
      width: 50%;
      max-width: 80%;
      max-height: 80vh;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      overflow: auto;
    }
  </style>
</head>

<body>

  <div class="card text-bg-dark mb-3" center">
    <div class="card-header">
      <b> <?= $titulo ?> </b>
    </div>
    <div class="card-body">
      <p class="card-text">
        <?= $contenido ?>
      </p>
    </div>
    <div class="card-footer">
      <a href="index.php">
        <button type="button" class="btn btn-primary">Volver</button>
      </a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
</body>

</html>