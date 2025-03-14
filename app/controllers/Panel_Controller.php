<?php
require_once __DIR__ . '/../../config/conexion.php'; // Conexión a la BD

try {
    // Consulta actualizada para traer TODOS los reportes sin limitación
    $stmt = $conn->prepare("SELECT folio, tipo_reporte, descripcion, imagen_hallazgo, estado, nombre_usuario, tel_usuario 
                            FROM reportes 
                            ORDER BY folio DESC");
    $stmt->execute();
    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
