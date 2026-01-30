<?php
include 'includes/conexion.php';

echo "<h3>🔐 ESTABLECIENDO CONTRASEÑA ESPECÍFICA</h3>";

$correo = "usuario@prueba.com";
$contraseña_deseada = "123456";
$hash_correcto = password_hash($contraseña_deseada, PASSWORD_DEFAULT);

echo "Configurando contraseña: <strong>{$contraseña_deseada}</strong><br>";

// Forzar la actualización
$sql = "UPDATE usuarios SET contraseña = ? WHERE correo = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ss", $hash_correcto, $correo);

if (mysqli_stmt_execute($stmt)) {
    echo "✅ CONTRASEÑA ESTABLECIDA<br><br>";
    
    // Verificar
    $sql_check = "SELECT contraseña FROM usuarios WHERE correo = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $correo);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_bind_result($stmt_check, $hash_actual);
    mysqli_stmt_fetch($stmt_check);
    
    echo "Hash en BD: " . $hash_actual . "<br>";
    
    $verificacion = password_verify($contraseña_deseada, $hash_actual);
    echo "Verificación: " . ($verificacion ? "✅ CORRECTA" : "❌ INCORRECTA") . "<br><br>";
    
    if ($verificacion) {
        echo "<h3 style='color: green;'>🎉 ¡LISTO!</h3>";
        echo "Ahora puedes hacer login con:<br>";
        echo "<strong>Correo:</strong> usuario@prueba.com<br>";
        echo "<strong>Contraseña:</strong> 123456<br><br>";
        echo "<a href='login.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔐 PROBAR LOGIN</a>";
    }
    
    mysqli_stmt_close($stmt_check);
} else {
    echo "❌ Error: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>