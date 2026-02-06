<?php
session_start();
include 'includes/conexion.php';
include 'includes/auth.php'; // Verifica sesión y permisos

// Solo roles autorizados pueden acceder
if (!in_array($_SESSION['rol'], ['Administrador', 'Director', 'Secretario'])) {
    echo "<h3>⛔ Acceso restringido</h3>";
    exit;
}

// Procesar registro si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $cedula = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $nivel = trim($_POST['nivel']);

    // Escapar datos
    $nombre = mysqli_real_escape_string($conexion, $nombre);
    $cedula = mysqli_real_escape_string($conexion, $cedula);
    $telefono = mysqli_real_escape_string($conexion, $telefono);
    $correo = mysqli_real_escape_string($conexion, $correo);
    $nivel = mysqli_real_escape_string($conexion, $nivel);

    // Verificar si cédula ya existe
    $check = mysqli_query($conexion, "SELECT id FROM docentes WHERE cedula = '$cedula'");
    if (mysqli_num_rows($check) > 0) {
        $error = "La cédula ingresada ya está registrada.";
    } else {
        // Insertar nuevo docente
        $sql = "INSERT INTO docentes (nombre, cedula, telefono, correo, nivel, foto) 
                VALUES ('$nombre', '$cedula', '$telefono', '$correo', '$nivel', 'uploads/690fda7a66610_img-4.jpeg')";
        if (mysqli_query($conexion, $sql)) {
            $success = "Docente registrado correctamente.";
        } else {
            $error = "Error al registrar el docente: " . mysqli_error($conexion);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Docente</title>
  <style>
    * {margin:0; padding:0; box-sizing:border-box;}
    body {font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 20px; min-height: 100vh;}
    
    .container {
        background: white;
        max-width: 500px;
        margin: 0 auto;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    h1 {text-align:center; color:#333; margin-bottom:25px; padding-bottom:15px; border-bottom:2px solid #667eea;}

    .form-group {margin-bottom:20px;}
    label {display:block; margin-bottom:8px; color:#333; font-weight:bold;}
    input, select {width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px;}

    .btn-group {display:flex; flex-direction:column; gap:15px; margin-top:20px;}
    .btn {padding:12px; border:none; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer; text-align:center; text-decoration:none; transition: all 0.3s;}
    
    .btn-guardar {background:#3498db; color:white;}
    .btn-guardar:hover {background:#2980b9;}
    
    .btn-ver {background:#27ae60; color:white;}
    .btn-ver:hover {background:#1e8449;}
    
    .btn-menu {background:#e67e22; color:white;}
    .btn-menu:hover {background:#ca6f1e;}

    .mensaje {padding:15px; border-radius:8px; margin-bottom:20px; text-align:center;}
    .exito {background:#d4edda; color:#155724; border:1px solid #c3e6cb;}
    .error {background:#f8d7da; color:#721c24; border:1px solid #f5c6cb;}

    .foto-preview {display:block; margin:0 auto 20px auto; width:120px; height:120px; border-radius:50%; object-fit:cover; border:2px solid #ddd;}
  </style>
</head>
<body>
  <div class="container">
    <h1>👩‍🏫 Registrar Nuevo Docente</h1>

    <?php if (!empty($success)): ?>
        <div class="mensaje exito"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="mensaje error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Imagen por defecto -->
    <img src="uploads/690fda7a66610_img-4.jpeg" alt="Foto docente" class="foto-preview">

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nombre Completo *</label>
            <input type="text" name="nombre" required>
        </div>
        <div class="form-group">
            <label>Cédula *</label>
            <input type="text" name="cedula" required>
        </div>
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono">
        </div>
        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="correo">
        </div>
        <div class="form-group">
            <label>Nivel *</label>
            <select name="nivel" required>
                <option value="">Seleccione nivel</option>
                <option value="1°">1°</option>
                <option value="2°">2°</option>
                <option value="3°">3°</option>
            </select>
        </div>
        <div class="form-group">
            <label>Foto de perfil:</label>
            <input type="file" name="foto" accept="image/jpeg">
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-guardar">Guardar Docente</button>
            <a href="gestion_docentes.php" class="btn btn-ver">👀 Ver Docentes Registrados</a>
            <a href="panel_alumnos.php" class="btn btn-menu">🏠 Volver al Menú Principal</a>
        </div>
    </form>
  </div>
</body>
</html>
