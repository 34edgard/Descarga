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

// Verificar si la tabla tiene los campos de promoción, si no, crearlos
$verificar_campos = mysqli_query($conexion, "SHOW COLUMNS FROM alumnos LIKE 'estado_promocion'");
if (mysqli_num_rows($verificar_campos) == 0) {
    // Agregar campos si no existen
    mysqli_query($conexion, "ALTER TABLE alumnos ADD COLUMN estado_promocion ENUM('Activo', 'Promovido', 'Graduado', 'Retirado') DEFAULT 'Activo'");
    mysqli_query($conexion, "ALTER TABLE alumnos ADD COLUMN nivel_siguiente ENUM('1°', '2°', '3°', 'Graduado') DEFAULT NULL");
}

// Procesar promoción si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['promocionar'])) {
    $alumno_id = $_POST['alumno_id'];
    $nivel_siguiente = $_POST['nivel_siguiente'];
    
    // Actualizar el alumno
    $sql = "UPDATE alumnos SET nivel_siguiente = ?, estado_promocion = 'Promovido' WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "si", $nivel_siguiente, $alumno_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $mensaje_exito = "✅ Alumno promovido exitosamente";
    } else {
        $mensaje_error = "❌ Error al promover alumno: " . mysqli_error($conexion);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Alumnos Inscritos</title>
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
    
    .barra-modulos {
      text-align: center;
      margin: 25px 0;
      display: flex;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    
    .boton {
      padding: 10px 20px;
      background: #667eea;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      min-width: 140px;
      justify-content: center;
      border: none;
      cursor: pointer;
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
    
    .boton-terciario {
      background: #f39c12;
    }
    
    .boton-terciario:hover {
      background: #d35400;
    }
    
    .boton-promocion {
      background: #9b59b6;
    }
    
    .boton-promocion:hover {
      background: #8e44ad;
    }
    
    .boton-peligro {
      background: #e74c3c;
    }
    
    .boton-peligro:hover {
      background: #c0392b;
    }
    
    .panel {
      max-width: 1000px;
      margin: 0 auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .panel h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
      font-size: 24px;
      border-bottom: 2px solid #667eea;
      padding-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }
    
    .alumno {
      display: flex;
      align-items: center;
      border-bottom: 1px solid #eee;
      padding: 20px 0;
      transition: all 0.3s ease;
    }
    
    .alumno:hover {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 20px 15px;
      margin: 0 -15px;
    }
    
    .alumno img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 10px;
      margin-right: 20px;
      border: 3px solid #e9ecef;
    }
    
    .alumno-info {
      flex: 1;
    }
    
    .alumno-info p {
      margin: 5px 0;
      color: #555;
    }
    
    .alumno-info strong {
      color: #333;
      min-width: 150px;
      display: inline-block;
    }
    
    .acciones {
      text-align: right;
      display: flex;
      gap: 8px;
      flex-direction: column;
      align-items: flex-end;
    }
    
    .acciones form {
      display: inline;
    }
    
    .acciones .boton {
      min-width: auto;
      padding: 8px 12px;
      font-size: 12px;
    }
    
    .select-promocion {
      padding: 6px 10px;
      border: 2px solid #667eea;
      border-radius: 6px;
      background: white;
      font-size: 12px;
      min-width: 120px;
      margin-bottom: 5px;
    }
    
    .btn-promocionar {
      background: #9b59b6;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      font-size: 11px;
      transition: all 0.3s ease;
      width: 100%;
    }
    
    .btn-promocionar:hover {
      background: #8e44ad;
      transform: translateY(-1px);
    }
    
    .estado-promocion {
      font-size: 11px;
      padding: 3px 8px;
      border-radius: 12px;
      font-weight: bold;
      margin-top: 5px;
    }
    
    .estado-activo {
      background: #28a745;
      color: white;
    }
    
    .estado-promovido {
      background: #17a2b8;
      color: white;
    }
    
    .estado-graduado {
      background: #6f42c1;
      color: white;
    }
    
    .sin-alumnos {
      text-align: center;
      padding: 40px 20px;
      color: #666;
    }
    
    .sin-alumnos p {
      font-size: 16px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    
    .estadisticas-rapidas {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 25px 0;
      flex-wrap: wrap;
    }
    
    .tarjeta-estadistica {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      min-width: 150px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .tarjeta-estadistica .numero {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    .tarjeta-estadistica .texto {
      font-size: 14px;
      opacity: 0.9;
    }
    
    .navegacion-inferior {
      text-align: center;
      margin: 30px 0;
      display: flex;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    
    .mensaje-exito {
      background: #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 8px;
      border-left: 4px solid #28a745;
      margin-bottom: 20px;
      text-align: center;
    }
    
    .mensaje-error {
      background: #f8d7da;
      color: #721c24;
      padding: 15px;
      border-radius: 8px;
      border-left: 4px solid #dc3545;
      margin-bottom: 20px;
      text-align: center;
    }
    
    /* BOTÓN CERRAR SESIÓN MÁS PEQUEÑO Y COMPACTO */
    .boton-cerrar-sesion {
      padding: 8px 12px !important;
      background: #e74c3c !important;
      color: white !important;
      border: none !important;
      border-radius: 8px !important;
      cursor: pointer !important;
      font-size: 12px !important;
      font-weight: bold !important;
      transition: all 0.3s ease !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 5px !important;
      min-width: auto !important;
      width: auto !important;
      justify-content: center !important;
      text-decoration: none !important;
      flex-shrink: 0 !important;
    }
    
    .boton-cerrar-sesion:hover {
      background: #c0392b !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3) !important;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .panel {
      animation: fadeIn 0.5s ease;
    }
    
    .alumno {
      animation: fadeIn 0.3s ease;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .alumno {
        flex-direction: column;
        text-align: center;
        padding: 15px;
      }
      
      .alumno img {
        margin-right: 0;
        margin-bottom: 15px;
      }
      
      .acciones {
        justify-content: center;
        margin-top: 15px;
        align-items: center;
        flex-direction: row;
        flex-wrap: wrap;
      }
      
      .barra-modulos {
        flex-direction: column;
        align-items: center;
      }
      
      .boton {
        width: 200px;
      }
      
      .boton-cerrar-sesion {
        padding: 6px 10px !important;
        font-size: 11px !important;
      }
      
      .select-promocion {
        min-width: 100px;
      }
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

  <div class="barra-modulos">
    <a href="formulario_inscripcion.php" class="boton boton-secundario">
      <i class="fas fa-plus"></i> Inscribir Alumno
    </a>
    <a href="formulario_docente.php" class="boton">
      <i class="fas fa-chalkboard-teacher"></i> Agregar Docente
    </a>
    <a href="estadisticas.php" class="boton boton-terciario">
      <i class="fas fa-chart-bar"></i> Ver Estadísticas
    </a>
    <a href="promocion.php" class="boton boton-promocion">
      <i class="fas fa-graduation-cap"></i> Promoción
    </a>
  </div>

  <div class="panel">
    <h2><i class="fas fa-list"></i> Alumnos Inscritos</h2>
    
    <!-- MENSAJES DE SESIÓN PARA ELIMINACIÓN -->
    <?php if (isset($_SESSION['mensaje_exito'])): ?>
      <div class="mensaje-exito">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje_error'])): ?>
      <div class="mensaje-error">
        <i class="fas fa-exclamation-triangle"></i> <?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
      </div>
    <?php endif; ?>

    <!-- MENSAJES LOCALES PARA PROMOCIÓN -->
    <?php if (isset($mensaje_exito)): ?>
      <div class="mensaje-exito">
        <i class="fas fa-check-circle"></i> <?php echo $mensaje_exito; ?>
      </div>
    <?php endif; ?>
    
    <?php if (isset($mensaje_error)): ?>
      <div class="mensaje-error">
        <i class="fas fa-exclamation-triangle"></i> <?php echo $mensaje_error; ?>
      </div>
    <?php endif; ?>
    
    <?php
      // Obtener estadísticas rápidas
      $total_alumnos = mysqli_query($conexion, "SELECT COUNT(*) as total FROM alumnos")->fetch_assoc()['total'];
      $total_nivel1 = mysqli_query($conexion, "SELECT COUNT(*) as total FROM alumnos WHERE nivel = '1°'")->fetch_assoc()['total'];
      $total_nivel2 = mysqli_query($conexion, "SELECT COUNT(*) as total FROM alumnos WHERE nivel = '2°'")->fetch_assoc()['total'];
      $total_nivel3 = mysqli_query($conexion, "SELECT COUNT(*) as total FROM alumnos WHERE nivel = '3°'")->fetch_assoc()['total'];
    ?>
    
    <div class="estadisticas-rapidas">
      <div class="tarjeta-estadistica">
        <div class="numero"><?php echo $total_alumnos; ?></div>
        <div class="texto">Total Alumnos</div>
      </div>
      <div class="tarjeta-estadistica">
        <div class="numero"><?php echo $total_nivel1; ?></div>
        <div class="texto">1° Nivel</div>
      </div>
      <div class="tarjeta-estadistica">
        <div class="numero"><?php echo $total_nivel2; ?></div>
        <div class="texto">2° Nivel</div>
      </div>
      <div class="tarjeta-estadistica">
        <div class="numero"><?php echo $total_nivel3; ?></div>
        <div class="texto">3° Nivel</div>
      </div>
    </div>
    
    <?php
      $resultado = mysqli_query($conexion, "SELECT * FROM alumnos ORDER BY nivel, nombre ASC");
      if (mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
          $foto = !empty($fila['foto']) ? $fila['foto'] : 'img/avatar_default.png';
          $condiciones = !empty($fila['condicion_medica']) ? $fila['condicion_medica'] : 'Ninguna';
          $estado_promocion = $fila['estado_promocion'] ?? 'Activo';
          $nivel_siguiente = $fila['nivel_siguiente'] ?? '';
          
          // Determinar clase CSS para el estado
          $clase_estado = '';
          switch($estado_promocion) {
            case 'Promovido': $clase_estado = 'estado-promovido'; break;
            case 'Graduado': $clase_estado = 'estado-graduado'; break;
            default: $clase_estado = 'estado-activo';
          }
          
          echo "<div class='alumno'>
                  <img src='{$foto}' alt='Foto del alumno' onerror=\"this.src='img/avatar_default.png'\">
                  <div class='alumno-info'>
                    <p><strong>Nombre:</strong> {$fila['nombre']}</p>
                    <p><strong>Cédula escolar:</strong> {$fila['cedula_escolar']}</p>
                    <p><strong>Edad:</strong> {$fila['edad']} años</p>
                    <p><strong>Nivel:</strong> {$fila['nivel']}</p>
                    <p><strong>Condiciones médicas:</strong> {$condiciones}</p>
                    <div class='estado-promocion {$clase_estado}'>
                      {$estado_promocion}" . ($nivel_siguiente ? " → {$nivel_siguiente}" : "") . "
                    </div>
                  </div>";

          if ($rol_usuario === 'Administrador' || $rol_usuario === 'Director') {
            echo "<div class='acciones'>
                    <!-- SISTEMA DE PROMOCIÓN -->
                    <form method='POST' action='' onsubmit=\"return confirm('¿Está seguro de promover a {$fila['nombre']}?')\">
                      <input type='hidden' name='alumno_id' value='{$fila['id']}'>
                      <select name='nivel_siguiente' class='select-promocion' required>
                        <option value=''>Promover a...</option>";
            
            // Opciones según el nivel actual
            $opciones = [];
            switch($fila['nivel']) {
              case '1°':
                $opciones = ['2°' => '2° Nivel', '3°' => '3° Nivel', 'Graduado' => 'Graduado'];
                break;
              case '2°':
                $opciones = ['3°' => '3° Nivel', 'Graduado' => 'Graduado'];
                break;
              case '3°':
                $opciones = ['Graduado' => 'Graduado'];
                break;
              default:
                $opciones = [];
            }
            
            foreach ($opciones as $valor => $texto) {
              $selected = $nivel_siguiente == $valor ? 'selected' : '';
              echo "<option value='{$valor}' {$selected}>{$texto}</option>";
            }
            
            echo "</select>
                      <button type='submit' name='promocionar' class='btn-promocionar'>
                        <i class='fas fa-graduation-cap'></i> Promover
                      </button>
                    </form>
                    
                    <!-- BOTONES EXISTENTES -->
                    <a href='editar_alumno.php?id={$fila['id']}' class='boton'>
                      <i class='fas fa-edit'></i> Editar
                    </a>
                    <form action='eliminar_alumno.php' method='POST' onsubmit=\"return confirm('¿Está seguro que desea eliminar a {$fila['nombre']}?')\">
                      <input type='hidden' name='id_alumno' value='{$fila['id']}'>
                      <button type='submit' class='boton boton-peligro'>
                        <i class='fas fa-trash'></i> Eliminar
                      </button>
                    </form>
                  </div>";
          }

          echo "</div>";
        }
      } else {
        echo "<div class='sin-alumnos'>
                <p><i class='fas fa-bullseye'></i> No hay alumnos inscritos aún</p>
                <a href='formulario_inscripcion.php' class='boton boton-secundario'>
                  <i class='fas fa-plus'></i> Inscribir primer alumno
                </a>
              </div>";
      }
      mysqli_close($conexion);
    ?>
  </div>

  <div class="navegacion-inferior">
    <a href="panel_alumnos.php" class="boton">
      <i class="fas fa-sync"></i> Actualizar
    </a>
    <button type="button" class="boton-cerrar-sesion" onclick="confirmarCerrarSesion()">
      <i class="fas fa-sign-out-alt"></i> Cerrar sesión
    </button>
  </div>

  <!-- Modal de confirmación para cerrar sesión -->
  <div id="modalCerrarSesion" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 15px; text-align: center; max-width: 400px; width: 90%; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
      <div style="font-size: 48px; color: #e74c3c; margin-bottom: 15px;">
        <i class="fas fa-sign-out-alt"></i>
      </div>
      <h3 style="color: #333; margin-bottom: 15px;">¿Realmente quiere cerrar sesión?</h3>
      <p style="color: #666; margin-bottom: 25px;">Se desconectará del sistema y deberá ingresar nuevamente para continuar.</p>
      <div style="display: flex; gap: 10px; justify-content: center;">
        <form action="logout.php" method="POST" style="display: inline;">
          <button type="submit" class="boton boton-peligro" style="min-width: 100px;">
            <i class="fas fa-check"></i> Sí
          </button>
        </form>
        <button type="button" class="boton boton-secundario" onclick="cerrarModal()" style="min-width: 100px;">
          <i class="fas fa-times"></i> No
        </button>
      </div>
    </div>
  </div>

  <script>
    function confirmarCerrarSesion() {
      document.getElementById('modalCerrarSesion').style.display = 'flex';
    }

    function cerrarModal() {
      document.getElementById('modalCerrarSesion').style.display = 'none';
    }

    // Cerrar modal si se hace clic fuera del contenido
    document.getElementById('modalCerrarSesion').addEventListener('click', function(e) {
      if (e.target === this) {
        cerrarModal();
      }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        cerrarModal();
      }
    });

    // Validar promoción antes de enviar
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function(e) {
        const select = this.querySelector('select[name="nivel_siguiente"]');
        if (!select.value) {
          e.preventDefault();
          alert('Por favor seleccione un nivel de promoción');
        }
      });
    });
  </script>
</body>
</html>