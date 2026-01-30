<?php
session_start();
include 'includes/conexion.php';

// Solo Administrador puede guardar
if ($_SESSION['rol'] !== 'Administrador') {
  echo "<h3>⛔ Acceso denegado</h3>";
  exit;
}

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$cedula = $_POST['cedula'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
$rol = $_POST['rol'];

// Foto
$foto_nombre = $_FILES['foto']['name'];
$foto_temp = $_FILES['foto']['tmp_name'];
$foto_ruta = "uploads/" . $foto_nombre;
move_uploaded_file($foto_temp, $foto_ruta);

// Insertar
$sql = "INSERT INTO usuarios (nombre, correo, cedula, contrasena, rol, foto)
        VALUES ('$nombre', '$correo', '$cedula', '$contrasena', '$rol', '$foto_ruta')";

if (mysqli_query($conexion, $sql)) {
  header("Location: usuarios.php");
  exit;
} else {
  echo "<h3>❌ Error: " . mysqli_error($conexion) . "</h3>";
}

mysqli_close($conexion);