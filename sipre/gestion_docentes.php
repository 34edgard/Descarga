<?php
session_start();
include 'includes/conexion.php';
include 'includes/auth.php'; // Verifica sesión y permisos

// Solo roles autorizados
if (!in_array($_SESSION['rol'], ['Administrador', 'Director', 'Secretario'])) {
    echo "<h3>⛔ Acceso restringido</h3>";
    exit;
}

$rol_usuario = $_SESSION['rol'];

// ===========================
// Paginación y búsqueda
// ===========================
$por_pagina = 10; // docentes por página
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $por_pagina;

$busqueda = '';
$where_sql = '';
if (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {
    $busqueda = trim($_GET['buscar']);
    $where_sql = "WHERE nombre LIKE '%$busqueda%' OR cedula LIKE '%$busqueda%'";
}

// Contar total de docentes para paginación
$total_sql = "SELECT COUNT(*) as total FROM docentes $where_sql";
$total_result = mysqli_query($conexion, $total_sql);
$total_fila = mysqli_fetch_assoc($total_result);
$total_docentes = $total_fila['total'];
$total_paginas = ceil($total_docentes / $por_pagina);

// Obtener docentes actuales
$sql = "SELECT * FROM docentes $where_sql ORDER BY nombre ASC LIMIT $inicio, $por_pagina";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Docentes</title>
  <link rel="stylesheet" href="css/estilos.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .panel {
      width: 100%;
      max-width: 900px;
      background-color: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .buscador {
      text-align: center;
      margin-bottom: 20px;
    }

    .buscador input[type="text"] {
      padding: 8px;
      width: 60%;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .buscador button {
      padding: 8px 12px;
      border-radius: 5px;
      border: none;
      background-color: #3498db;
      color: white;
      cursor: pointer;
    }

    .buscador button:hover {
      background-color: #2980b9;
    }

    .docente {
      display: flex;
      align-items: center;
      border-bottom: 1px solid #eee;
      padding: 15px 0;
    }

    .docente img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 50%;
      margin-right: 20px;
    }

    .docente-info {
      flex: 1;
    }

    .docente-info p {
      margin: 4px 0;
    }

    .acciones {
      text-align: right;
    }

    .acciones form, .acciones a {
      display: inline-block;
      margin-left: 5px;
    }

    .acciones button, .acciones a {
      padding: 6px 10px;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      color: white;
      font-weight: bold;
    }

    .acciones button {
      background-color: #e74c3c;
      border: none;
    }

    .acciones button:hover {
      background-color: #c0392b;
    }

    .acciones a {
      background-color: #f39c12;
    }

    .acciones a:hover {
      background-color: #d68910;
    }

    .botones-final {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 25px;
    }

    .botones-final a {
      display: inline-block;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      color: white;
      text-align: center;
    }

    .btn-menu {
      background-color: #27ae60;
    }

    .btn-menu:hover {
      background-color: #1e8449;
    }

    .btn-registrar {
      background-color: #3498db;
    }

    .btn-registrar:hover {
      background-color: #2980b9;
    }

    .paginacion {
      text-align: center;
      margin-top: 20px;
    }

    .paginacion a {
      display: inline-block;
      margin: 0 5px;
      padding: 6px 12px;
      background-color: #3498db;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }

    .paginacion a:hover {
      background-color: #2980b9;
    }

    .paginacion .actual {
      background-color: #2ecc71;
    }
  </style>
</head>
<body>
  <div class="panel">
    <h2>👩‍🏫 Docentes Registrados</h2>

    <!-- Buscador -->
    <div class="buscador">
      <form method="GET">
        <input type="text" name="buscar" placeholder="Buscar por nombre o cédula" value="<?php echo htmlspecialchars($busqueda); ?>">
        <button type="submit">🔍 Buscar</button>
      </form>
    </div>

    <?php
      if(mysqli_num_rows($resultado) > 0){
          while ($fila = mysqli_fetch_assoc($resultado)) {
              $foto_docente = !empty($fila['foto']) ? $fila['foto'] : "uploads/690fda7a66610_img-4.jpeg";

              echo "<div class='docente'>
                      <img src='{$foto_docente}' alt='Foto'>
                      <div class='docente-info'>
                        <p><strong>Nombre:</strong> {$fila['nombre']}</p>
                        <p><strong>Cédula:</strong> {$fila['cedula']}</p>
                        <p><strong>Teléfono:</strong> {$fila['telefono']}</p>
                        <p><strong>Correo:</strong> {$fila['correo']}</p>
                        <p><strong>Nivel:</strong> {$fila['nivel']}</p>
                      </div>
                      <div class='acciones'>";

              // Botón Editar (Todos los roles autorizados)
              echo "<a href='editar_docente.php?id={$fila['id']}'>✏️ Editar</a>";

              // Solo Director o Administrador pueden eliminar
              if (in_array($rol_usuario, ['Administrador', 'Director'])) {
                  echo "<form action='eliminar_docente.php' method='POST' onsubmit=\"return confirm('¿Eliminar este docente?')\">
                          <input type='hidden' name='id_docente' value='{$fila['id']}'>
                          <button type='submit'>🗑 Eliminar</button>
                        </form>";
              }

              echo "</div></div>";
          }
      } else {
          echo "<p style='text-align: center;'>No hay docentes registrados.</p>";
      }

      mysqli_close($conexion);
    ?>

    <!-- Paginación -->
    <?php if ($total_paginas > 1): ?>
      <div class="paginacion">
        <?php for($i=1; $i<=$total_paginas; $i++): ?>
          <a class="<?php echo $i==$pagina?'actual':''; ?>" href="?pagina=<?php echo $i; ?>&buscar=<?php echo urlencode($busqueda); ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>

    <!-- Botones finales: Volver y Registrar -->
    <div class="botones-final">
      <a href="panel_alumnos.php" class="btn-menu">🏠 Volver al Menú Principal</a>
      <a href="formulario_docente.php" class="btn-registrar">➕ Registrar Docente</a>
    </div>
  </div>
</body>
</html>
