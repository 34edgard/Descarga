<?php
session_start();
include 'includes/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['rol'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenida al Sistema SIPRE- URUGUAY</title>
  <link rel="stylesheet" href="css/estilos.css">
  <style>
    body {
      background-color: #f4f6f9;
      font-family: Arial, sans-serif;
    }
    .panel {
      max-width: 600px;
      margin: auto;
      margin-top: 40px;
      background-color: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    .panel h2 {
      margin-bottom: 20px;
    }
    .panel img {
      max-width: 100px;
      margin-bottom: 15px;
    }
    .opciones a {
      display: block;
      margin: 10px auto;
      padding: 12px;
      background-color: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      width: 80%;
    }
    .opciones a:hover {
      background-color: #2980b9;
    }
    .usuario-info {
      font-size: 14px;
      color: #555;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="panel">
    <img src="img/logo.png" alt="Logo institucional">
    <h2>Bienvenido al Sistema SIPRE</h2>
    <div class="usuario-info">
      <strong><?php echo $nombre_usuario; ?></strong> (<?php echo $rol_usuario; ?>)
    </div>

    <div class="opciones">
      <a href="panel_alumnos.php">📋 Ver alumnos inscritos</a>

      <?php if ($rol_usuario === 'Administrador' || $rol_usuario === 'Directora' || $rol_usuario === 'Secretario'): ?>
        <a href="registrar_alumno.php">➕ Registrar nuevo alumno</a>
      <?php endif; ?>

      <?php if ($rol_usuario === 'Administrador'): ?>
        <a href="usuarios.php">👥 Administrar usuarios</a>
        <a href="registrar_usuario.php">➕ Registrar nuevo usuario</a>
      <?php endif; ?>

      <a href="logout.php">🔒 Cerrar sesión</a>
    </div>
  </div>
</body>
</html>