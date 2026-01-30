<?php
include 'includes/conexion.php';

$nombre = "Usuario General";
$correo = "usuario@prueba.com";
$contraseña_plana = "123456";
$cedula = "1234567890";
$rol = "Director";

// Generar hash CORRECTO
$contraseña_hash = password_hash($contraseña_plana, PASSWORD_DEFAULT);

echo "<h3>🔧 Creando Usuario Correctamente</h3>";
echo "<strong>Para login usar:</strong><br>";
echo "Correo: " . $correo . "<br>";
echo "Contraseña: " . $contraseña_plana . "<br><br>";

// Verificar si existe
$check_sql = "SELECT id FROM usuarios WHERE correo = ?";
$check_stmt = mysqli_prepare($conexion, $check_sql);
mysqli_stmt_bind_param($check_stmt, "s", $correo);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    // Actualizar
    $sql = "UPDATE usuarios SET contraseña = ? WHERE correo = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $contraseña_hash, $correo);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "✅ Usuario ACTUALIZADO - Ahora puede hacer login";
    } else {
        echo "❌ Error: " . mysqli_error($conexion);
    }
} else {
    // Insertar nuevo
    $sql = "INSERT INTO usuarios (nombre, correo, contraseña, cedula, rol, foto) VALUES (?, ?, ?, ?, ?, 'img/logo.png')";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $nombre, $correo, $contraseña_hash, $cedula, $rol);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "✅ Usuario CREADO - Ahora puede hacer login";
    } else {
        echo "❌ Error: " . mysqli_error($conexion);
    }
}

mysqli_stmt_close($check_stmt);
if (isset($stmt)) mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>