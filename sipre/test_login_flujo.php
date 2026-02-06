<?php
include 'includes/conexion.php';

$correo = 'admin@sipre.local';
$clave_ingresada = 'admin12345';

$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($fila = mysqli_fetch_assoc($resultado)) {
  echo "✅ Usuario encontrado<br>";
  echo "Hash en BD: " . $fila['contraseña'] . "<br>";

  if (password_verify($clave_ingresada, $fila['contraseña'])) {
    echo "✅ La contraseña coincide<br>";
  } else {
    echo "❌ La contraseña NO coincide<br>";
  }
} else {
  echo "❌ Usuario no encontrado<br>";
}
