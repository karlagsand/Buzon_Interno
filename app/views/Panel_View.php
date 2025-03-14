<?php require_once __DIR__ . "/../../app/controllers/Panel_Controller.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Reportes</title>
  <!-- Favicon -->
  <link rel="icon" href="../../public/img/favicon.png" type="image/png">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables CSS para Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
  <!-- Font Awesome para íconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Hoja de estilos global -->
  <link href="../../public/css/styles.css" rel="stylesheet">
  <link rel="icon" href="../../public/img/iconAIFA.png" type="image/png">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
  <!-- Header global -->
  <header class="text-center">
    <img src="../../public/img/logoAIFA.png" alt="Logo AIFA" class="header-logo">
  </header>

  <!-- Contenido principal -->
  <main class="flex-grow-1">
    <div class="container main-content panel-container">
      <h1 class="fw-bold text-center">Panel de Reportes</h1>

      <div class="d-flex justify-content-end mb-3">
        <a href="/NUEVA_ESTRUCTURA/app/controllers/ExportarReporte_Controller.php" id="exportExcel" class="btn btn-success me-2">
          📊 Exportar a Excel
        </a>
        <a href="/NUEVA_ESTRUCTURA/app/controllers/Logout_Controller.php" class="btn btn-danger">
          🔒 Cerrar Sesión
        </a>
      </div>

      <!-- Contenedor de la tabla -->
      <div class="table-container">
        <table id="reportTable" class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Folio</th>
              <th>Tipo de Reporte</th>
              <th class="col-descripcion">Descripción</th>
              <th>Imagen</th>
              <th>Estado</th>
              <th class="col-usuario">Usuario</th>
              <th>Teléfono</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reportes as $reporte): ?>
              <tr>
                <td><?php echo htmlspecialchars($reporte['folio']); ?></td>
                <td><?php echo htmlspecialchars($reporte['tipo_reporte']); ?></td>
                <td class="col-descripcion">
                  <?php echo nl2br(htmlspecialchars($reporte['descripcion'])); ?>
                </td>
                <td>
                  <?php 
                    if (!empty($reporte['imagen_hallazgo'])) {
                      $nombre_archivo = basename($reporte['imagen_hallazgo']);
                      $ruta_imagen = "../../admin/evidencias_usuarios/" . $nombre_archivo;
                      if (file_exists(__DIR__ . "/../../admin/evidencias_usuarios/" . $nombre_archivo)) {
                        echo '<img src="'. htmlspecialchars($ruta_imagen) .'" 
                                 class="img-thumbnail"
                                 width="50" height="50"
                                 alt="Evidencia"
                                 onclick="showImage(\''. htmlspecialchars($ruta_imagen) .'\')">';
                      } else {
                        echo '<span class="text-danger">Imagen no encontrada</span>';
                      }
                    } else {
                      echo '<span class="text-muted">Sin evidencia</span>';
                    }
                  ?>
                </td>
                <td class="text-center">
                  <button class="btn btn-link p-0 toggle-status" 
                          data-folio="<?php echo $reporte['folio']; ?>" 
                          data-status="<?php echo strtolower($reporte['estado']); ?>" 
                          onclick="toggleStatus(this)">
                    <?php if (strtolower($reporte['estado']) == 'atendido'): ?>
                      <i class="fas fa-check-circle text-success" title="Atendido"></i>
                    <?php else: ?>
                      <i class="fas fa-exclamation-triangle text-danger" title="Pendiente"></i>
                    <?php endif; ?>
                  </button>
                </td>
                <td class="col-usuario">
                  <?php echo nl2br(htmlspecialchars($reporte['nombre_usuario'])); ?>
                </td>
                <td><?php echo htmlspecialchars($reporte['tel_usuario']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Footer global -->
  <footer class="text-center">
    <p>&copy; <?php echo date("Y"); ?> Aeropuerto Internacional Felipe Ángeles</p>
  </footer>

  <!-- Modal de imagen en pantalla completa -->
  <div id="imageModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content bg-transparent border-0">
        <div class="modal-header border-0">
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body d-flex justify-content-center align-items-center">
          <img id="modalImage" src="" class="img-fluid" alt="Imagen de Evidencia">
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#reportTable').DataTable({
        language: {
          "emptyTable": "No hay información",
          "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
          "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
          "infoFiltered": "(filtrado de _MAX_ entradas totales)",
          "lengthMenu": "Mostrar _MENU_ entradas",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscar:",
          "zeroRecords": "No se encontraron coincidencias",
          "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
          }
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        pageLength: 10
      });
    });

    function showImage(imageSrc) {
      document.getElementById('modalImage').src = imageSrc;
      var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
      myModal.show();
    }

    function toggleStatus(button) {
      var folio = $(button).data('folio');
      var currentStatus = $(button).data('status');
      var newStatus = (currentStatus === 'pendiente') ? 'Atendido' : 'Pendiente';

      $.ajax({
        url: '../controllers/UpdateStatus_Controller.php',
        method: 'POST',
        data: {
          folio: folio,
          new_status: newStatus
        },
        success: function(response) {
          console.log("Respuesta del servidor:", response);
          try {
            var res = JSON.parse(response);
            if (res.success) {
              $(button).data('status', newStatus.toLowerCase());
              if (newStatus.toLowerCase() === 'atendido') {
                $(button).html('<i class="fas fa-check-circle text-success" title="Atendido"></i>');
              } else {
                $(button).html('<i class="fas fa-exclamation-triangle text-danger" title="Pendiente"></i>');
              }
            } else {
              alert('Error al actualizar el estado en la base de datos.');
            }
          } catch(e) {
            console.error("Error al parsear JSON:", e);
            alert('La respuesta del servidor no es válida.');
          }
        },
        error: function(xhr, status, error) {
          alert('Error en la solicitud AJAX: ' + error);
          console.error("AJAX Error:", xhr, status, error);
        }
      });
    }
  </script>
</body>
</html>
