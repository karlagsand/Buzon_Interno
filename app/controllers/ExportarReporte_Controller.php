<?php
// ExportarReporte_Controller.php

require_once __DIR__ . '/../../config/conexion.php';

// Incluir la librería SimpleXLSXGen y usar su namespace
require_once __DIR__ . '/../../lib/SimpleXLSXGen.php';
use Shuchkin\SimpleXLSXGen;

try {
    // Extraer todos los reportes en orden descendente por folio
    $stmt = $conn->prepare("
        SELECT folio, tipo_reporte, descripcion, imagen_hallazgo, estado, nombre_usuario, tel_usuario 
        FROM reportes 
        ORDER BY folio DESC
    ");
    $stmt->execute();
    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Preparar los datos para el Excel: primera fila con encabezados
    $data = [];
    $data[] = ['Folio', 'Tipo de Reporte', 'Descripción', 'Imagen', 'Estado', 'Usuario', 'Teléfono'];
    
    // Agregar una fila por cada reporte
    foreach ($reportes as $row) {
        $data[] = [
            $row['folio'],
            $row['tipo_reporte'],
            $row['descripcion'],
            $row['imagen_hallazgo'], // Puedes ajustar si deseas solo el nombre o una URL
            $row['estado'],
            $row['nombre_usuario'],
            $row['tel_usuario']
        ];
    }

    // Generar el nombre del archivo con la fecha actual
    $filename = "Reportes Buzón Interno de la Dirección de Operación - " . date("Y-m-d") . ".xlsx";

    // Genera y fuerza la descarga del archivo Excel con la hoja llamada "Reportes"
    SimpleXLSXGen::fromArray($data, "Reportes", $options)->downloadAs($filename);
    
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
