<?php
session_start();
session_destroy(); // Cerrar sesión

header("Location: ../../app/views/Login_View.php");
exit;
?>
