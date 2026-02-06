<?php
$host = "";
$usuario = "";
$password = "";
$base_datos = "";

// Crear conexión
$conexion = mysqli_connect($host, $usuario, $password, $base_datos);

// Validar conexión
if (!$conexion) {
    die("❌ Error de conexión: " . mysqli_connect_error());
}

// Configurar charset para evitar problemas con acentos y caracteres especiales
mysqli_set_charset($conexion, "utf8mb4");

// Opcional: habilitar errores de mysqli para desarrollo
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
