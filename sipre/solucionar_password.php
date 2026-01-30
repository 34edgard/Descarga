<?php
include 'includes/conexion.php';

echo "<h3>🔧 SOLUCIONANDO CONTRASEÑA VACÍA</h3>";

$correo = "usuario@prueba.com";
$nueva_contraseña = "123456";
$nuevo_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

echo "Correo: " . $correo . "<br>";
echo "Nueva contraseña: " . $nueva_contraseña . "<br>";
echo "Nuevo hash: " . $nuevo_hash . "<br><br>";

// Actualizar la contraseña vacía
$sql = "UPDATE usuarios SET contraseña = ? WHERE correo = ? AND (contraseña = '' OR contraseña IS NULL)";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ss", $nuevo_hash, $correo);

if (mysqli_stmt_execute($stmt)) {
    $filas_afectadas = mysqli_stmt_affected_rows($stmt);
    
    if ($filas_afectadas > 0) {
        echo "✅ CONTRASEÑA ACTUALIZADA EXITOSAMENTE<br><br>";
        
        // Verificar que ahora funciona
        $sql_verificar = "SELECT contraseña FROM usuarios WHERE correo = ?";
        $stmt_verificar = mysqli_prepare($conexion, $sql_verificar);
        mysqli_stmt_bind_param($stmt_verificar, "s", $correo);
        mysqli_stmt_execute($stmt_verificar);
        mysqli_stmt_bind_result($stmt_verificar, $hash_actual);
        mysqli_stmt_fetch($stmt_verificar);
        
        echo "Hash en BD ahora: " . $hash_actual . "<br>";
        $verificacion = password_verify($nueva_contraseña, $hash_actual);
        echo "Password verify ahora: " . ($verificacion ? "✅ FUNCIONA" : "❌ NO FUNCIONA") . "<br><br>";
        
        echo "<strong>🎉 ¡AHORA PUEDES HACER LOGIN!</strong><br>";
        echo "Usa: <strong>usuario@prueba.com</strong> / <strong>123456</strong>";
        
        mysqli_stmt_close($stmt_verificar);
    } else {
        echo "⚠️ No se pudo actualizar. Probablemente ya tiene una contraseña.<br>";
        
        // Ver qué hay realmente en la BD
        $sql_ver = "SELECT contraseña, LENGTH(contraseña) as largo FROM usuarios WHERE correo = ?";
        $stmt_ver = mysqli_prepare($conexion, $sql_ver);
        mysqli_stmt_bind_param($stmt_ver, "s", $correo);
        mysqli_stmt_execute($stmt_ver);
        mysqli_stmt_bind_result($stmt_ver, $pass_real, $largo);
        mysqli_stmt_fetch($stmt_ver);
        
        echo "Contraseña actual: '" . $pass_real . "'<br>";
        echo "Longitud: " . $largo . " caracteres<br>";
        
        mysqli_stmt_close($stmt_ver);
    }
} else {
    echo "❌ Error al actualizar: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>