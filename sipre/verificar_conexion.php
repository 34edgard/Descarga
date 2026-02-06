<?php
include 'includes/conexion.php';

echo "<h3>🔍 Verificando Conexión y Usuario</h3>";

if ($conexion) {
    echo "✅ Conexión a BD exitosa<br>";
    
    $correo = "usuario@prueba.com";
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        
        if ($fila = mysqli_fetch_assoc($resultado)) {
            echo "✅ Usuario encontrado<br>";
            echo "Hash almacenado: " . $fila['contraseña'] . "<br>";
            
            $verif = password_verify("123456", $fila['contraseña']);
            echo "Password verify: " . ($verif ? "✅ FUNCIONA" : "❌ NO FUNCIONA");
            
        } else {
            echo "❌ Usuario NO encontrado";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "❌ Error en la consulta preparada";
    }
} else {
    echo "❌ Error de conexión a la BD";
}

mysqli_close($conexion);
