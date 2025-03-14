<?php 
session_start();
require_once realpath(__DIR__ . '/../models/Reporte_Model.php');

class ReporteController {
    private $reporteModel;
    private $directorio_destino;

    public function __construct() {
        $this->reporteModel = new ReporteModel();
        $this->directorio_destino = __DIR__ . '/../../admin/evidencias_usuarios/';

        // Crear la carpeta si no existe
        if (!is_dir($this->directorio_destino)) {
            mkdir($this->directorio_destino, 0777, true);
        }
    }

    public function procesarFormulario($postData, $fileData) {
        $nombre = $postData['nombre'] ?? null;
        $telefono = $postData['telefono'] ?? null;
        $tipo_reporte = $postData['tipo_reporte'] ?? null;
        $descripcion = $postData['descripcion'] ?? null;

        // **Generar el folio**
        $folio = $this->reporteModel->generarFolio(); 

        // **Subir y renombrar la imagen**
        $imagen_nombre = $this->subirImagen($fileData['imagen'] ?? null, $folio);

        // **Guardar en la base de datos**
        $resultado = $this->reporteModel->guardarReporte($folio, $tipo_reporte, $descripcion, $imagen_nombre, $nombre, $telefono);

        return $resultado ? "exito" : "error";
    }

    private function subirImagen($imagen, $folio) {
        if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
            $fecha_actual = date("dmY_His"); // Formato: 04032024_153045
            $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION); // Obtener la extensión

            // Renombrar la imagen con el formato folio_fecha.extensión
            $nombre_archivo = $folio . "_" . $fecha_actual . "." . $extension;
            $ruta_final = $this->directorio_destino . $nombre_archivo;

            // Mover el archivo y retornar solo el nombre de la imagen
            if (move_uploaded_file($imagen['tmp_name'], $ruta_final)) {
                return $nombre_archivo; // Solo el nombre, no la ruta completa
            }
        }
        return null; // Si no hay imagen, se guarda NULL
    }
}

// **Ejecutar el controlador**
$controlador = new ReporteController();
$resultado = $controlador->procesarFormulario($_POST, $_FILES);

if ($resultado === "exito") {
    header("Location: /NUEVA_ESTRUCTURA/index.php?mensaje=exito");
} else {
    header("Location: /NUEVA_ESTRUCTURA/index.php?mensaje=error");
}
exit;
?>
