<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

echo "<h1>Prueba de funcionamiento</h1>";
echo "<p>Si ves este mensaje, PHP está funcionando.</p>";

// Probar conexión a BD
$conexion = mysqli_connect("localhost", "tu_usuario", "tu_password", "tu_base_datos");
if ($conexion) {
    echo "<p style='color: green;'>✓ Conexión a BD exitosa</p>";
    
    // Probar consulta simple
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM alumnos");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "<p>Total alumnos: " . $row['total'] . "</p>";
    } else {
        echo "<p style='color: red;'>✗ Error en consulta: " . mysqli_error($conexion) . "</p>";
    }
    
    mysqli_close($conexion);
} else {
    echo "<p style='color: red;'>✗ Error de conexión BD: " . mysqli_connect_error() . "</p>";
}
?>