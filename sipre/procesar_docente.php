<?php
session_start();
include 'includes/conexion.php';

// Proteger acceso
if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtener y sanitizar datos
  $nombre = mysqli_real_escape_string($conexion, $_POST['nombre_docente']);
  $cedula = mysqli_real_escape_string($conexion, $_POST['cedula_docente']);
  $telefono = mysqli_real_escape_string($conexion, $_POST['telefono_docente']);
  $correo = mysqli_real_escape_string($conexion, $_POST['correo_docente']);

  // Validar campos obligatorios
  if (empty($nombre) || empty($cedula) || empty($telefono)) {
    $_SESSION['error'] = "Por favor complete todos los campos obligatorios";
    header("Location: registro_docente.php");
    exit;
  }

  // Insertar en la base de datos - USANDO LOS NOMBRES CORRECTOS DE COLUMNAS
  $query = "INSERT INTO docentes (nombre, cedula, telefono, correo) 
            VALUES ('$nombre', '$cedula', '$telefono', '$correo')";

  if (mysqli_query($conexion, $query)) {
    $_SESSION['exito'] = "Docente registrado exitosamente";
    header("Location: gestion_docentes.php");
    exit;
  } else {
    $_SESSION['error'] = "Error al registrar docente: " . mysqli_error($conexion);
    header("Location: registro_docente.php");
    exit;
  }
} else {
  header("Location: registro_docente.php");
  exit;
}