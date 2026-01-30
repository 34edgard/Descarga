<?php
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "sipre";

$conexion = mysqli_connect($host, $usuario, $password, $base_datos);

if (!$conexion) {
  die("Error de conexión: " . mysqli_connect_error());
}

// 🚨 ESTA LÍNEA FALTA Y ES CRÍTICA 🚨
mysqli_set_charset($conexion, "utf8mb4");

// Opcional: para debug
// echo "✅ Conexión establecida correctamente";
?>