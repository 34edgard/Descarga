<?php
include 'includes/conexion.php';

echo "<h3>🔧 SOLUCIÓN DEFINITIVA</h3>";

$correo = "usuario@prueba.com";
$nueva_contraseña = "123456";
$nuevo_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

// SOLUCIÓN 1: Actualizar sin condiciones
$sql1 = "UPDATE usuarios SET contraseña = ? WHERE correo = ?";
$stmt1 = mysqli_prepare($conexion, $sql1);
mysqli_stmt_bind_param($stmt1, "ss", $nuevo_hash, $correo);

if (mysqli_stmt_execute($stmt1)) {
    echo "✅ CONTRASEÑA ACTUALIZADA<br>";
    
    // Verificar
    $sql_check = "SELECT contraseña FROM usuarios WHERE correo = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $correo);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_bind_result($stmt_check, $hash_final);
    mysqli_stmt_fetch($stmt_check);
    
    echo "Hash final: " . $hash_final . "<br>";
    
    $verif = password_verify($nueva_contraseña, $hash_final);
    echo "Verificación: " . ($verif ? "✅ ÉXITO" : "❌ FALLO") . "<br><br>";
    
    if ($verif) {
        echo "<h3 style='color: green;'>🎉 ¡PROBLEMA SOLUCIONADO!</h3>";
        echo "Ahora puedes hacer login con:<br>";
        echo "<strong>Correo:</strong> usuario@prueba.com<br>";
        echo "<strong>Contraseña:</strong> 123456<br>";
        echo "<br><a href='login.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔐 IR AL LOGIN</a>";
    }
    
    mysqli_stmt_close($stmt_check);
} else {
    echo "❌ Error: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt1);
mysqli_close($conexion);
?>