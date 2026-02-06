<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

$rol_usuario = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios del Sistema</title>
  <link rel="stylesheet" href="css/estilos.css">
  <style>
    .panel { max-width: 800px; margin: auto; margin-top: 40px; background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
    .usuario { display: flex; align-items: center; border-bottom: 1px solid #eee; padding: 15px 0;}
    .usuario img { width: 60px; height: 60px; object-fit: cover; border-radius: 50%; margin-right: 20px;}
    .usuario-info { flex: 1;}
    .usuario-info p { margin: 4px 0;}
    .acciones { text-align: right;}
    .acciones form { display: inline;}
    .acciones button { background-color: #e74c3c; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer;}
    .acciones button:hover { background-color: #c0392b;}
  </style>
</head>
<body>
  <div class="panel">
    <h2>👥 Usuarios del Sistema</h2>
    <?php
      $resultado = mysqli_query($conexion, "SELECT * FROM usuarios ORDER BY nombre ASC");
      while ($fila = mysqli_fetch_assoc($resultado)) {
        echo "<div class='usuario'>
                <img src='{$fila['foto']}' alt='Foto'>
                <div class='usuario-info'>
                  <p><strong>Nombre:</strong> {$fila['nombre']}</p>
                  <p><strong>Correo:</strong> {$fila['correo']}</p>
                  <p><strong>Rol:</strong> {$fila['rol']}</p>
                </div>";

        if ($rol_usuario === 'Administrador') {
          echo "<div class='acciones'>
                  <form action='eliminar_usuario.php' method='POST' onsubmit=\"return confirm('¿Eliminar este usuario?')\">
                    <input type='hidden' name='id_usuario' value='{$fila['id']}'>
                    <button type='submit'>🗑 Eliminar</button>
                  </form>
                </div>";
        }

        echo "</div>";
      }
      mysqli_close($conexion);
    ?>
  </div>
</body>
</html>
