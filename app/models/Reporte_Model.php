<?php
require_once realpath(__DIR__ . '/../../config/conexion.php');

class ReporteModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function generarFolio() {
        // Obtener el año actual
        $anioActual = date("Y");

        // Buscar el último folio registrado en el año actual
        $stmt = $this->conn->prepare("SELECT ultimo_folio FROM folios WHERE ano = ?");
        $stmt->execute([$anioActual]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $ultimoFolio = $resultado['ultimo_folio'] + 1;

            // Actualizar el último folio en la tabla
            $stmt = $this->conn->prepare("UPDATE folios SET ultimo_folio = ? WHERE ano = ?");
            $stmt->execute([$ultimoFolio, $anioActual]);
        } else {
            // Si no existe, se crea un nuevo registro
            $ultimoFolio = 1;
            $stmt = $this->conn->prepare("INSERT INTO folios (ultimo_folio, ano) VALUES (?, ?)");
            $stmt->execute([$ultimoFolio, $anioActual]);
        }

        // Formatear el folio con ceros a la izquierda (ejemplo: FOLIO-0001-2025)
        $folio = sprintf("FOLIO-%04d-%d", $ultimoFolio, $anioActual);
        return $folio;
    }

    public function guardarReporte($folio, $tipo, $descripcion, $imagen, $nombre, $telefono) {
        $stmt = $this->conn->prepare("INSERT INTO reportes (folio, nombre_usuario, tel_usuario, tipo_reporte, descripcion, imagen_hallazgo, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$folio, $nombre, $telefono, $tipo, $descripcion, $imagen]);
    }
}
?>
