<?php
session_start();
include 'includes/conexion.php';

// Proteger acceso
if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['rol'] ?? '';

// Obtener lista de docentes - USANDO LOS NOMBRES CORRECTOS DE COLUMNAS
$query = "SELECT * FROM docentes ORDER BY nombre ASC";
$resultado = mysqli_query($conexion, $query);

$total_docentes = mysqli_num_rows($resultado);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Docentes</title>
  <link rel="stylesheet" href="css/estilos.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      margin: 0;
      padding: 20px;
      min-height: 100vh;
    }
    
    .encabezado {
      text-align: center;
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      max-width: 1000px;
      margin: 0 auto 25px auto;
    }
    
    .encabezado img {
      max-width: 80px;
      margin-bottom: 10px;
    }
    
    .usuario-info {
      text-align: center;
      font-size: 14px;
      color: #666;
      margin-top: 10px;
    }
    
    .usuario-info strong {
      color: #333;
      font-size: 16px;
    }
    
    .panel {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      max-width: 1000px;
      margin: 0 auto 25px auto;
    }
    
    .panel h2 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
      font-size: 24px;
      border-bottom: 2px solid #667eea;
      padding-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }
    
    .estadistica-rapida {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .estadistica-rapida .numero {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    .estadistica-rapida .texto {
      font-size: 16px;
      opacity: 0.9;
    }
    
    .boton {
      padding: 12px 25px;
      background: #667eea;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      min-width: 120px;
      margin: 5px;
      justify-content: center;
    }
    
    .boton:hover {
      background: #5a6fd8;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    
    .boton-secundario {
      background: #2ecc71;
    }
    
    .boton-secundario:hover {
      background: #27ae60;
    }
    
    .boton-peligro {
      background: #e74c3c;
    }
    
    .boton-peligro:hover {
      background: #c0392b;
    }
    
    .centrar-botones {
      text-align: center;
      margin: 25px 0 10px 0;
    }
    
    .grupo-botones {
      display: flex;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
      margin: 20px 0;
    }
    
    /* Estilos para la tabla de docentes */
    .tabla-contenedor {
      overflow-x: auto;
      margin: 25px 0;
    }
    
    .tabla-docentes {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .tabla-docentes th {
      background: #667eea;
      color: white;
      padding: 15px;
      text-align: left;
      font-weight: bold;
      border: none;
    }
    
    .tabla-docentes td {
      padding: 12px 15px;
      border-bottom: 1px solid #f0f0f0;
      color: #333;
    }
    
    .tabla-docentes tr:hover {
      background: #f8f9ff;
    }
    
    .tabla-docentes tr:last-child td {
      border-bottom: none;
    }
    
    .acciones {
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
    }
    
    .btn-accion {
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 12px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: all 0.3s ease;
    }
    
    .btn-editar {
      background: #f39c12;
      color: white;
    }
    
    .btn-editar:hover {
      background: #d35400;
    }
    
    .btn-eliminar {
      background: #e74c3c;
      color: white;
    }
    
    .btn-eliminar:hover {
      background: #c0392b;
    }
    
    .sin-registros {
      text-align: center;
      padding: 40px;
      color: #666;
      font-style: italic;
      background: #f8f9fa;
      border-radius: 10px;
      margin: 20px 0;
    }
    
    .sin-registros i {
      font-size: 48px;
      margin-bottom: 15px;
      color: #ccc;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .panel {
      animation: fadeIn 0.5s ease;
    }
  </style>
</head>
<body>
  <div class="encabezado">
    <img src="img/logo.png" alt="Logo institucional">
    <h1 style="color: #333; margin: 10px 0 5px 0;">Sistema Escolar</h1>
    <div class="usuario-info">
      <strong><?php echo $nombre_usuario; ?></strong> (<?php echo $rol_usuario; ?>)
    </div>
  </div>

  <div class="panel">
    <h2><i class="fas fa-chalkboard-teacher"></i> Gestión de Docentes</h2>
    
    <div class="estadistica-rapida">
      <div class="numero"><?php echo $total_docentes; ?></div>
      <div class="texto">Docentes Registrados</div>
    </div>

    <div class="centrar-botones">
      <a href="registro_docente.php" class="boton boton-secundario">
        <i class="fas fa-plus"></i> Nuevo Docente
      </a>
      <a href="panel_alumnos.php" class="boton">
        <i class="fas fa-home"></i> Menú Principal
      </a>
    </div>

    <div class="tabla-contenedor">
      <?php if ($total_docentes > 0): ?>
        <table class="tabla-docentes">
          <thead>
            <tr>
              <th>Nombre Completo</th>
              <th>Cédula</th>
              <th>Teléfono</th>
              <th>Correo</th>
              <th>Nivel</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($docente = mysqli_fetch_assoc($resultado)): ?>
              <tr>
                <td><?php echo htmlspecialchars($docente['nombre']); ?></td>
                <td><?php echo htmlspecialchars($docente['cedula']); ?></td>
                <td><?php echo htmlspecialchars($docente['telefono']); ?></td>
                <td>
                  <?php if (!empty($docente['correo'])): ?>
                    <?php echo htmlspecialchars($docente['correo']); ?>
                  <?php else: ?>
                    <span style="color: #999; font-style: italic;">No registrado</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($docente['nivel'])): ?>
                    <?php echo htmlspecialchars($docente['nivel']); ?>
                  <?php else: ?>
                    <span style="color: #999; font-style: italic;">No asignado</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="acciones">
                    <a href="editar_docente.php?id=<?php echo $docente['id']; ?>" class="btn-accion btn-editar">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="eliminar_docente.php?id=<?php echo $docente['id']; ?>" class="btn-accion btn-eliminar" onclick="return confirm('¿Está seguro de eliminar este docente?')">
                      <i class="fas fa-trash"></i> Eliminar
                    </a>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="sin-registros">
          <i class="fas fa-users-slash"></i>
          <h3>No hay docentes registrados</h3>
          <p>No se han encontrado docentes en el sistema.</p>
          <a href="registro_docente.php" class="boton boton-secundario" style="margin-top: 15px;">
            <i class="fas fa-plus"></i> Registrar primer docente
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="grupo-botones">
    <a href="registro_docente.php" class="boton boton-secundario">
      <i class="fas fa-plus"></i> Nuevo Docente
    </a>
    <a href="panel_alumnos.php" class="boton">
      <i class="fas fa-home"></i> Menú Principal
    </a>
    <form action="logout.php" method="POST" style="display: inline;">
      <button type="submit" class="boton boton-peligro">
        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
      </button>
    </form>
  </div>
</body>
</html>