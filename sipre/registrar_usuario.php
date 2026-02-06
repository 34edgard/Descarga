<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/conexion.php';

if ($_SESSION['rol'] !== 'Administrador') {
  echo "<h3>⛔ Acceso restringido</h3>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Usuario</title>
  <link rel="stylesheet" href="css/estilos.css">
  <style>
    .formulario { max-width: 500px; margin: auto; margin-top: 40px; background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
    .formulario h2 { text-align: center; margin-bottom: 20px;}
    .formulario input, .formulario select { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #ccc;}
    .formulario button { width: 100%; padding: 10px; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;}
    .formulario button:hover { background-color: #2980b9;}
  </style>
</head>
<body>
  <div class="formulario">
    <h2>Registrar Nuevo Usuario</h2>
    <form action="guardar_usuario.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="nombre" placeholder="Nombre completo" required>
      <input type="email" name="correo" placeholder="Correo institucional" required>
      <input type="text" name="cedula" placeholder="Cédula" required>
      <input type="password" name="contrasena" placeholder="Contraseña" required>
      <select name="rol" required>
        <option value="">Seleccione rol</option>
        <option value="Administrador">Administrador</option>
        <option value="Director">Director</option>
        <option value="Secretario">Secretario</option>
      </select>
      <label>Foto de perfil:</label>
      <input type="file" name="foto" accept="image/*">
      <button type="submit">Guardar usuario</button>
    </form>
  </div>
</body>
</html>
