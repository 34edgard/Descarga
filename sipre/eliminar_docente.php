<?php
session_start();
include 'includes/conexion.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener ID del docente
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    // Redireccionar si no hay ID válido
    echo "<script>window.location.href = 'gestion_docentes.php';</script>";
    exit;
}

// Obtener nombre del docente para el mensaje
$query = "SELECT nombre FROM docentes WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    echo "<script>
            alert('Docente no encontrado');
            window.location.href = 'gestion_docentes.php';
          </script>";
    exit;
}

$docente = mysqli_fetch_assoc($resultado);
$nombre_docente = $docente['nombre'];

// Eliminar el docente
$eliminar = "DELETE FROM docentes WHERE id = $id";

if (mysqli_query($conexion, $eliminar)) {
    // Éxito - Redireccionar con mensaje
    echo "<script>
            alert('Docente \"$nombre_docente\" eliminado correctamente');
            window.location.href = 'gestion_docentes.php?mensaje=eliminado';
          </script>";
} else {
    // Error
    echo "<script>
            alert('Error al eliminar el docente: " . addslashes(mysqli_error($conexion)) . "');
            window.location.href = 'gestion_docentes.php';
          </script>";
}

// Cerrar conexión
mysqli_close($conexion);
exit;
?>