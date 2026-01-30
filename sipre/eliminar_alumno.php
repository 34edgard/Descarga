<?php
session_start();
include 'includes/conexion.php';

// Verificar sesión activa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verificar rol autorizado (Administrador o Director)
$rol_usuario = $_SESSION['rol'] ?? '';
$id_alumno = $_POST['id_alumno'] ?? '';

// Solo Administrador o Director pueden eliminar alumnos
if (($rol_usuario === 'Administrador' || $rol_usuario === 'Director') && is_numeric($id_alumno)) {
    
    // Primero verificamos que el alumno existe
    $sql_verificar = "SELECT nombre FROM alumnos WHERE id = '$id_alumno'";
    $resultado = mysqli_query($conexion, $sql_verificar);
    
    if (mysqli_num_rows($resultado) > 0) {
        // El alumno existe, procedemos a eliminar
        $sql_eliminar = "DELETE FROM alumnos WHERE id = '$id_alumno'";
        
        if (mysqli_query($conexion, $sql_eliminar)) {
            $_SESSION['mensaje_exito'] = "✅ Alumno eliminado exitosamente";
        } else {
            $_SESSION['mensaje_error'] = "❌ Error al eliminar el alumno: " . mysqli_error($conexion);
        }
    } else {
        $_SESSION['mensaje_error'] = "❌ El alumno no existe o ya fue eliminado";
    }
    
} else {
    $_SESSION['mensaje_error'] = "⛔ No tiene permisos para eliminar alumnos o el ID es inválido";
}

mysqli_close($conexion);
header("Location: panel_alumnos.php");
exit;
?>