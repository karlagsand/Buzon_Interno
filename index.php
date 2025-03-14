<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buzón Interno de la Dirección de Operación</title>

  <link rel="icon" href="public/img/iconAIFA.png" type="image/png">
  <link href="public/css/bootstrap.min.css" rel="stylesheet">
  <link href="public/css/styles.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
  <header class="text-center">
    <img src="public/img/logoAIFA.png" alt="Logo AIFA" class="header-logo">
  </header>

  <main class="flex-grow-1">
    <div class="container main-content">
      <h1>Buzón Interno de la Dirección de Operación</h1>
      <h4>“Tu participación cuenta”</h4>
      <!-- Se agrega la clase "equal" para tener el mismo margen lateral y vertical -->
      <div class="btn-container center equal">
        <!-- Se forzará la presentación vertical con la clase "list" -->
        <div class="btn-row list">
          <a href="app/views/Formulario_View.php?area=Guardia Nacional" class="btn-access btn-primary btn-index">Guardia Nacional</a>
          <a href="app/views/Formulario_View.php?area=Módulos de información AIFA" class="btn-access btn-primary btn-index">Módulos de información AIFA</a>
          <a href="app/views/Formulario_View.php?area=Ecodeli" class="btn-access btn-primary btn-index">Ecodeli</a>
        </div>
      </div>
    </div>
  </main>

  <footer class="text-center mt-auto">
    <p>&copy; <?php echo date("Y"); ?> Aeropuerto Internacional Felipe Ángeles</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="public/js/bootstrap.bundle.min.js"></script>
</body>
</html>
