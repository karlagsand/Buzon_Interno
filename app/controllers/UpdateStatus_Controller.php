<?php
require_once __DIR__ . '/../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $folio = $_POST['folio'] ?? null;
    $new_status = $_POST['new_status'] ?? null;

    if ($folio && $new_status) {
        try {
            $stmt = $conn->prepare("
                UPDATE reportes 
                SET estado = :new_status 
                WHERE folio = :folio
            ");
            $stmt->bindParam(':new_status', $new_status, PDO::PARAM_STR);
            $stmt->bindParam(':folio', $folio, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Parámetros inválidos']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
