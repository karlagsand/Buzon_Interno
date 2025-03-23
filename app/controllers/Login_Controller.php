<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';

// Recibir credenciales del formulario
$correo     = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

try {
    // Consulta segura con sentencias preparadas
    $stmt = $conn->prepare("SELECT id_admin, correo, contrasena FROM administrador WHERE correo = :correo LIMIT 1");
    $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
    $stmt->execute();
    
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el usuario y la contraseña coincide
    if ($admin) {
        // Nota: Se recomienda usar password_verify si las contraseñas están hasheadas.
        if ($contrasena === $admin['contrasena']) { 
            // Almacena en sesión el id del administrador
            $_SESSION['user_id'] = $admin['id_admin']; 
            // Almacena el correo si se necesita
            $_SESSION['admin'] = $admin['correo'];
            // Marca la actividad actual
            $_SESSION['LAST_ACTIVITY'] = time();
            header("Location: ./../views/Panel_View.php");
            exit;
        } else {
            header("Location: ./../views/Login_View.php?error=1"); // Contraseña incorrecta
            exit;
        }
    } else {
        header("Location: ./../views/Login_View.php?error=2"); // Usuario no encontrado
        exit;
    }
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
