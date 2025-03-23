<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Administrador</title>
  <link rel="icon" href="../../public/img/iconAIFA.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../public/css/styles.css" rel="stylesheet">
</head>
<!-- Se remueve bg-light y se agrega inline style para el fondo gris (#e0e0e0) -->
<body class="d-flex flex-column min-vh-100" style="background-color: #e0e0e0;">
  <!-- Header (se estiliza desde styles.css) -->
  <header class="text-center">
    <img src="../../public/img/logoAIFA.png" alt="Logo AIFA" class="header-logo">
  </header>

  <!-- Contenido principal -->
  <main class="flex-grow-1">
    <div class="container main-content">
      <div class="login-container">
        <h2 class="text-center">Iniciar Sesión</h2>
        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger">Credenciales incorrectas. Intenta de nuevo.</div>
        <?php endif; ?>
        <form action="../../app/controllers/Login_Controller.php" method="POST">
          <div class="mb-3">
            <label for="usuario" class="form-label">Usuario:</label>
            <input type="text" class="form-control" id="usuario" name="usuario" required>
          </div>
          <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
          </div>
          <div class="d-flex justify-content-center gap-2">
            <button type="submit" class="btn-access btn-enviar btn-form">Entrar</button>
            <a href="../../index.php" class="btn-access btn-regresar btn-form">Regresar</a>
          </div>
        </form>
      </div>
    </div>
  </main>

  <!-- Footer (unificado) -->
  <footer class="text-center">
    <p>&copy; <?php echo date("Y"); ?> Aeropuerto Internacional Felipe Ángeles</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
