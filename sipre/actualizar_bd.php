<?php
include 'includes/conexion.php';

echo "<h2>⚙️ Actualizando Base de Datos...</h2>";

// Verificar si existe la columna 'seccion_id' en 'alumnos'
$check = mysqli_query($conexion, "SHOW COLUMNS FROM alumnos LIKE 'seccion_id'");

if (mysqli_num_rows($check) == 0) {
    // No existe, la creamos
    $sql = "ALTER TABLE alumnos ADD COLUMN seccion_id INT(11) NULL AFTER docente_id";
    if (mysqli_query($conexion, $sql)) {
        echo "<p style='color:green; font-weight:bold;'>✅ Columna 'seccion_id' creada exitosamente en la tabla 'alumnos'.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error al crear columna: " . mysqli_error($conexion) . "</p>";
    }
} else {
    echo "<p style='color:blue;'>ℹ️ La columna 'seccion_id' ya existía. No se hicieron cambios.</p>";
}

echo "<br><a href='modulo_reportes.php'>Ir al Módulo de Reportes</a>";
?>