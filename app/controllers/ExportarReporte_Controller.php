<?php
// ExportarReporte_Controller.php

require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

try {
    // Extraer todos los reportes en orden descendente por folio
    $stmt = $conn->prepare("
        SELECT folio, tipo_reporte, descripcion, imagen_hallazgo, estado, nombre_usuario, tel_usuario 
        FROM reportes 
        ORDER BY folio DESC
    ");
    $stmt->execute();
    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reportes');

// 1. Fila de título (fila 1): fondo azul (#0d2632), texto blanco y centrado
$title = "Reportes del Buzón Interno de la Dirección de Operación";
$sheet->mergeCells('A1:G1');
$sheet->setCellValue('A1', $title);
$titleStyle = $sheet->getStyle('A1');
$titleStyle->getFont()->setBold(true)->setSize(16)->getColor()->setARGB('FFFFFFFF'); // Letra blanca
$titleStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                            ->setVertical(Alignment::VERTICAL_CENTER);
$titleStyle->getFill()->setFillType(Fill::FILL_SOLID)
          ->getStartColor()->setARGB('FF0D2632'); // Azul (#0d2632)
// Ajustar la altura de la fila de título
$sheet->getRowDimension(1)->setRowHeight(30);

// 2. Fila de encabezados (fila 2): fondo gris (#6c757d), texto blanco, centrado y con bordes
$headers = ['Folio', 'Tipo de Reporte', 'Descripción', 'Imagen', 'Estado', 'Usuario', 'Teléfono'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '2', $header);
    $col++;
}
$headerRange = 'A2:G2';
$headerStyle = $sheet->getStyle($headerRange);
$headerStyle->getFill()->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF6C757D'); // Gris (#6c757d)
$headerStyle->getFont()->setBold(true)
    ->getColor()->setARGB('FFFFFFFF'); // Texto blanco
$headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                            ->setVertical(Alignment::VERTICAL_CENTER);

// 3. Agregar los datos a partir de la fila 3
$rowNumber = 3;
foreach ($reportes as $row) {
    $sheet->setCellValue('A' . $rowNumber, $row['folio']);
    $sheet->setCellValue('B' . $rowNumber, $row['tipo_reporte']);
    $sheet->setCellValue('C' . $rowNumber, $row['descripcion']);
    $sheet->setCellValue('D' . $rowNumber, $row['imagen_hallazgo']);
    $sheet->setCellValue('E' . $rowNumber, $row['estado']);
    $sheet->setCellValue('F' . $rowNumber, $row['nombre_usuario']);
    $sheet->setCellValue('G' . $rowNumber, $row['tel_usuario']);
    $rowNumber++;
}

// 4. Aplicar bordes a todas las celdas de la tabla (desde la fila 1 hasta la última)
$lastRow = $rowNumber - 1;
$allRange = "A1:G" . $lastRow;
$sheet->getStyle($allRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// 5. Ajustar el ancho de las columnas automáticamente
foreach (range('A', 'G') as $colID) {
    $sheet->getColumnDimension($colID)->setAutoSize(true);
}

// 6. Preparar la descarga del archivo Excel
$filename = "Reportes Buzón Interno de la Dirección de Operación - " . date("Y-m-d") . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
?>
