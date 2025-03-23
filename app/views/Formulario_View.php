<?php
$area = isset($_GET['area']) ? htmlspecialchars($_GET['area']) : 'Área Desconocida';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Incidencias - <?php echo $area; ?></title>
  <link rel="icon" href="../../public/img/iconAIFA.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../public/css/styles.css" rel="stylesheet">
</head>
<!-- Cambiamos el fondo del body a gris (#e0e0e0) -->
<body class="d-flex" style="background-color: #e0e0e0;">
  <header class="text-center">
    <img src="../../public/img/logoAIFA.png" alt="Logo AIFA" class="header-logo">
  </header>
  
  <main class="flex-grow-1">
    <div class="container main-content">
      <h1 class="fw-bold text-center">Registro de Incidencias<br><?php echo $area; ?></h1>
      
      <!-- Modificamos el contenedor de formulario para que tenga fondo blanco -->
      <div class="btn-container left" style="background-color: #fff;">
        <form id="reporteForm" action="../../app/controllers/GuardarReporte_Controller.php" method="POST" enctype="multipart/form-data" novalidate>
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre (Opcional):</label>
            <input type="text" class="form-control" id="nombre" name="nombre">
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono (Opcional):</label>
            <input type="text" class="form-control" id="telefono" name="telefono" pattern="[0-9]{10}" maxlength="10">
            <small id="telefonoError" class="text-danger" style="display:none;">Revise su número telefónico, debe contener 10 dígitos.</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipo de Reporte:</label>
            <div>
              <input type="radio" id="comentario" name="tipo_reporte" value="comentario">
              <label for="comentario">Comentario o Sugerencia</label>
              <input type="radio" id="necesidad" name="tipo_reporte" value="necesidad">
              <label for="necesidad">Necesidad</label>
              <input type="radio" id="ecodeli" name="tipo_reporte" value="ecodeli">
              <label for="ecodeli">Ecodeli</label>
            </div>
            <small id="tipoReporteError" class="text-danger" style="display:none;">Por favor, selecciona un tipo de reporte.</small>
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción del Reporte:</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Favor de incluir información que permita atender o dar seguimiento a su reporte (lugar, fecha, hora, etc.)"></textarea>
            <small id="descripcionError" class="text-danger" style="display:none;">La descripción es obligatoria y debe tener al menos 10 caracteres.</small>
          </div>
          <div class="mb-3">
            <label for="imagen" class="form-label">Adjuntar Imagen (Opcional):</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/png, image/jpeg, image/jpg">
            <small id="imagenError" class="text-danger" style="display:none;">Solo se permiten imágenes en formato JPG, JPEG o PNG.</small>
          </div>
          
          <div class="btn-group-access">
            <div class="btn-row">
              <button type="submit" class="btn-access btn-enviar btn-form">Enviar</button>
              <a href="../../index.php" class="btn-access btn-regresar btn-form">Regresar</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
  
  <footer class="text-center mt-auto">
    <p>&copy; <?php echo date("Y"); ?> Aeropuerto Internacional Felipe Ángeles</p>
  </footer>
  
  <!-- Modal de Confirmación -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="confirmationModalLabel">Reporte enviado con éxito</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          ¡Agradecemos tu valioso tiempo para compartir tu opinión con nosotros!
        </div>
        <div class="modal-footer bg-light">
          <button type="button" id="confirmSubmit" class="btn btn-primary">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('reporteForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevenir envío inmediato
      
      let tipoReporte = document.querySelector('input[name="tipo_reporte"]:checked');
      let descripcion = document.getElementById('descripcion').value.trim();
      let telefono = document.getElementById('telefono').value.trim();
      let imagen = document.getElementById('imagen').value;
      let telefonoError = document.getElementById('telefonoError');
      let tipoReporteError = document.getElementById('tipoReporteError');
      let descripcionError = document.getElementById('descripcionError');
      let imagenError = document.getElementById('imagenError');
      let isValid = true;
      let telefonoRegex = /^[0-9]{10}$/;
      
      if (telefono && !telefonoRegex.test(telefono)) {
        telefonoError.style.display = 'block';
        isValid = false;
      } else {
        telefonoError.style.display = 'none';
      }
      
      if (!tipoReporte) {
        tipoReporteError.style.display = 'block';
        isValid = false;
      } else {
        tipoReporteError.style.display = 'none';
      }
      
      if (descripcion.length < 10) {
        descripcionError.style.display = 'block';
        isValid = false;
      } else {
        descripcionError.style.display = 'none';
      }
      
      if (imagen) {
        let extPermitidas = /\.(jpg|jpeg|png)$/i;
        if (!extPermitidas.exec(imagen)) {
          imagenError.style.display = 'block';
          isValid = false;
        } else {
          imagenError.style.display = 'none';
        }
      }
      
      if (!isValid) {
        return; // No se envía el formulario si hay errores
      } else {
        // Mostrar el modal de confirmación
        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        confirmationModal.show();
        
        // Al hacer clic en "Aceptar" se envía el formulario
        document.getElementById('confirmSubmit').addEventListener('click', function() {
          confirmationModal.hide();
          document.getElementById('reporteForm').submit();
        }, {once: true});
      }
    });
  </script>
</body>
</html>
