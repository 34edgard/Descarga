<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/conexion.php';

// Solo Administrador puede eliminar usuarios
if ($_SESSION['rol'] !== 'Administrador') {
    echo "<h3>⛔ Acceso restringido</h3>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: usuarios.php");
        exit;
    } else {
        echo "❌ Error al eliminar: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "❌ Petición no válida";
}

mysqli_close($conexion);
