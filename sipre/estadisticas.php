<?php
session_start();
include 'includes/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['rol'] ?? '';

// Obtener estadísticas básicas
$total_alumnos = 0;
$total_docentes = 0;
$total_hombres = 0;
$total_mujeres = 0;

$result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM alumnos");
if ($result) $total_alumnos = $result->fetch_assoc()['total'];

$result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM docentes");
if ($result) $total_docentes = $result->fetch_assoc()['total'];

$result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM alumnos WHERE sexo = 'M'");
if ($result) $total_hombres = $result->fetch_assoc()['total'];

$result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM alumnos WHERE sexo = 'F'");
if ($result) $total_mujeres = $result->fetch_assoc()['total'];

// Obtener tipo de estadística
$tipo_estadistica = $_GET['tipo'] ?? 'sexo';
$datos_grafico = [];
$titulo_grafico = '';

switch($tipo_estadistica) {
    case 'sexo':
        $titulo_grafico = 'Distribución por Sexo';
        $result = mysqli_query($conexion, "SELECT sexo, COUNT(*) as total FROM alumnos GROUP BY sexo");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $label = $row['sexo'] == 'M' ? 'Masculino' : 'Femenino';
                $datos_grafico[] = ['label' => $label, 'total' => $row['total']];
            }
        }
        break;
        
    case 'edad':
        $titulo_grafico = 'Distribución por Edad';
        $result = mysqli_query($conexion, "SELECT 
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 0 AND 2 THEN '0-2 años'
                WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 3 AND 4 THEN '3-4 años' 
                WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 5 AND 6 THEN '5-6 años'
                ELSE '7 años o más'
            END as grupo_edad,
            COUNT(*) as total 
            FROM alumnos 
            GROUP BY grupo_edad");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $datos_grafico[] = ['label' => $row['grupo_edad'], 'total' => $row['total']];
            }
        }
        break;
        
    case 'nivel':
        $titulo_grafico = 'Distribución por Nivel';
        $result = mysqli_query($conexion, "SELECT nivel, COUNT(*) as total FROM alumnos GROUP BY nivel ORDER BY nivel");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $datos_grafico[] = ['label' => $row['nivel'], 'total' => $row['total']];
            }
        }
        break;
        
    default:
        $titulo_grafico = 'Distribución por Sexo';
        $result = mysqli_query($conexion, "SELECT sexo, COUNT(*) as total FROM alumnos GROUP BY sexo");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $label = $row['sexo'] == 'M' ? 'Masculino' : 'Femenino';
                $datos_grafico[] = ['label' => $label, 'total' => $row['total']];
            }
        }
}

$total_general = 0;
if (!empty($datos_grafico)) {
    $total_general = array_sum(array_column($datos_grafico, 'total'));
}

// Verificar si mostrar reporte general
$mostrar_reporte = isset($_GET['reporte']) && $_GET['reporte'] == 'general';

// FUNCIÓN PARA GENERAR REPORTE GENERAL CON TODOS LOS DATOS
function generarReporteGeneral($conexion) {
    $sql = "SELECT 
        a.id,
        a.nombre as alumno_nombre,
        a.fecha_nacimiento,
        a.sexo,
        a.aula,
        a.turno,
        a.condicion_medica,
        r.nombre as representante_nombre,
        r.apellido as representante_apellido,
        r.cedula as representante_cedula,
        r.telefono as representante_telefono,
        r.direccion as representante_direccion,
        r.profesion_representante,
        d.nombre as docente_nombre,
        d.apellido as docente_apellido
    FROM alumnos a
    LEFT JOIN representantes r ON a.representante_id = r.id
    LEFT JOIN docentes d ON a.docente_id = d.id
    ORDER BY a.aula, a.turno, a.nombre
    LIMIT 200";
    
    return mysqli_query($conexion, $sql);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Estadísticas - Sistema Educativo</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #667eea;
      margin: 0;
      padding: 20px;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .header {
      text-align: center;
      background: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    
    .panel {
      background: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin: 20px 0;
    }
    
    .stat-card {
      background: #667eea;
      color: white;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
    }
    
    .stat-number {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    .stat-label {
      font-size: 14px;
    }
    
    /* BOTONES SIMPLES */
    .btn {
      display: inline-block;
      padding: 10px 15px;
      background: #667eea;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      margin: 5px;
      font-size: 14px;
    }
    
    .btn:hover {
      background: #5a6fd8;
    }
    
    .btn-secondary {
      background: #2ecc71;
    }
    
    .btn-secondary:hover {
      background: #27ae60;
    }
    
    .btn-tertiary {
      background: #f39c12;
    }
    
    .btn-tertiary:hover {
      background: #d35400;
    }
    
    .btn-danger {
      background: #e74c3c;
    }
    
    .btn-danger:hover {
      background: #c0392b;
    }
    
    .btn-print {
      background: #9b59b6;
    }
    
    .btn-print:hover {
      background: #8e44ad;
    }
    
    /* NAVEGACIÓN INFERIOR - VISIBLE */
    .bottom-nav {
      text-align: center;
      margin: 30px 0;
      padding: 20px;
      background: white;
      border-radius: 10px;
    }
    
    .bottom-nav .btn {
      display: inline-block;
      margin: 5px;
    }
    
    .filter-buttons {
      text-align: center;
      margin: 20px 0;
    }
    
    .data-cards {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: center;
      margin: 20px 0;
    }
    
    .data-card {
      background: white;
      border: 2px solid #667eea;
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      min-width: 120px;
    }
    
    .data-value {
      font-size: 24px;
      font-weight: bold;
      color: #667eea;
      margin-bottom: 5px;
    }
    
    .data-label {
      font-size: 12px;
      color: #666;
      font-weight: bold;
    }
    
    .data-percent {
      font-size: 11px;
      color: #28a745;
      margin-top: 5px;
    }
    
    /* REPORTE GENERAL */
    .full-report {
      margin-top: 30px;
      background: white;
      border-radius: 10px;
      padding: 20px;
    }
    
    .report-header {
      text-align: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #667eea;
    }
    
    .class-group {
      margin-bottom: 30px;
    }
    
    .class-title {
      background: #667eea;
      color: white;
      padding: 10px 15px;
      border-radius: 5px;
      margin-bottom: 15px;
    }
    
    .report-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 11px;
    }
    
    .report-table th {
      background: #667eea;
      color: white;
      padding: 10px 6px;
      text-align: left;
      font-weight: bold;
    }
    
    .report-table td {
      padding: 8px 6px;
      border-bottom: 1px solid #eee;
      vertical-align: top;
    }
    
    .report-table tr:nth-child(even) {
      background: #f8f9fa;
    }
    
    .condition-badge {
      display: inline-block;
      padding: 2px 6px;
      border-radius: 10px;
      font-size: 9px;
      font-weight: bold;
      margin: 1px;
    }
    
    .condition-asma { background: #ffeaa7; color: #d35400; }
    .condition-alergia { background: #fab1a0; color: #c23616; }
    .condition-especial { background: #a29bfe; color: white; }
    .condition-otros { background: #fd79a8; color: white; }
    
    .empty-data {
      color: #999;
      font-style: italic;
    }
    
    /* MODAL DE CONFIRMACIÓN */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }
    
    .modal-content {
      background-color: white;
      padding: 25px;
      border-radius: 8px;
      width: 90%;
      max-width: 400px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .modal-header {
      margin-bottom: 15px;
    }
    
    .modal-header h3 {
      color: #2c3e50;
    }
    
    .modal-body {
      margin-bottom: 20px;
    }
    
    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    
    .btn-cancel {
      background-color: #95a5a6;
      color: white;
    }
    
    .btn-confirm {
      background-color: #e74c3c;
      color: white;
    }
    
    @media (max-width: 768px) {
      .bottom-nav .btn {
        display: block;
        width: 90%;
        margin: 10px auto;
      }
      
      .report-table {
        font-size: 10px;
      }
      
      .report-table th,
      .report-table td {
        padding: 6px 4px;
      }
      
      .modal-content {
        width: 95%;
        padding: 15px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>📊 Sistema de Estadísticas</h1>
      <p>Usuario: <strong><?php echo $nombre_usuario; ?></strong></p>
    </div>

    <div class="panel">
      <h2>Estadísticas y Reportes</h2>
      
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-number"><?php echo $total_alumnos; ?></div>
          <div class="stat-label">Total Alumnos</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $total_docentes; ?></div>
          <div class="stat-label">Total Docentes</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $total_hombres; ?></div>
          <div class="stat-label">Alumnos (H)</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $total_mujeres; ?></div>
          <div class="stat-label">Alumnas (M)</div>
        </div>
      </div>

      <div class="filter-buttons">
        <a href="?tipo=sexo" class="btn">Por Sexo</a>
        <a href="?tipo=edad" class="btn">Por Edad</a>
        <a href="?tipo=nivel" class="btn">Por Nivel</a>
        <a href="?reporte=general" class="btn btn-tertiary">📋 Informe General</a>
        <button class="btn btn-print" onclick="window.print()">🖨️ Imprimir</button>
      </div>

      <?php if (!$mostrar_reporte): ?>
      <!-- Área de Estadísticas (solo se muestra si NO estamos viendo el reporte) -->
      <div class="chart-container">
        <h3><?php echo $titulo_grafico; ?></h3>
        
        <?php if (!empty($datos_grafico)): ?>
          <div class="data-cards">
            <?php foreach ($datos_grafico as $dato): 
              $porcentaje = $total_general > 0 ? round(($dato['total'] / $total_general) * 100, 1) : 0;
            ?>
            <div class="data-card">
              <div class="data-value"><?php echo $dato['total']; ?></div>
              <div class="data-label"><?php echo $dato['label']; ?></div>
              <div class="data-percent"><?php echo $porcentaje; ?>%</div>
            </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p>No hay datos disponibles.</p>
        <?php endif; ?>
      </div>
      <?php else: ?>
      <!-- Reporte General (solo se muestra si estamos viendo el reporte) -->
      <div class="full-report">
        <div class="report-header">
          <h2>COLEGIO "JOSÉ AGUSTÍN MÉNDEZ GARCÍA"</h2>
          <h3>Reporte General Completo de Alumnos</h3>
          <p>Generado el: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>

        <?php
        $reporte_data = generarReporteGeneral($conexion);
        $alumnos_por_aula = [];
        
        if ($reporte_data && mysqli_num_rows($reporte_data) > 0) {
            while ($alumno = mysqli_fetch_assoc($reporte_data)) {
                $aula = $alumno['aula'] ?: 'Sin aula asignada';
                if (!isset($alumnos_por_aula[$aula])) {
                    $alumnos_por_aula[$aula] = [];
                }
                $alumnos_por_aula[$aula][] = $alumno;
            }
        }
        
        if (!empty($alumnos_por_aula)):
            foreach ($alumnos_por_aula as $aula => $alumnos): 
        ?>
          <div class="class-group">
            <h4 class="class-title">Aula: <?php echo $aula; ?> - <?php echo count($alumnos); ?> alumnos</h4>
            <table class="report-table">
              <thead>
                <tr>
                  <th>Alumno</th>
                  <th>Edad</th>
                  <th>Sexo</th>
                  <th>Turno</th>
                  <th>Condiciones</th>
                  <th>Representante</th>
                  <th>Cédula</th>
                  <th>Teléfono</th>
                  <th>Dirección</th>
                  <th>Profesión</th>
                  <th>Docente</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($alumnos as $alumno): 
                  $edad = $alumno['fecha_nacimiento'] ? date_diff(date_create($alumno['fecha_nacimiento']), date_create('today'))->y : 'N/A';
                  
                  // Determinar condiciones médicas
                  $condiciones = [];
                  if (!empty($alumno['condicion_medica'])) {
                    if (stripos($alumno['condicion_medica'], 'asma') !== false) $condiciones[] = 'Asma';
                    if (stripos($alumno['condicion_medica'], 'alergia') !== false) $condiciones[] = 'Alergia';
                    if (stripos($alumno['condicion_medica'], 'discapacidad') !== false) $condiciones[] = 'Condicion Especial';
                    if (empty($condiciones)) $condiciones[] = 'Otros';
                  }
                ?>
                <tr>
                  <td><strong><?php echo $alumno['alumno_nombre'] ?: '<span class="empty-data">No registrado</span>'; ?></strong></td>
                  <td style="text-align: center;"><?php echo $edad; ?> años</td>
                  <td style="text-align: center;"><?php echo $alumno['sexo'] == 'M' ? 'M' : 'F'; ?></td>
                  <td style="text-align: center;"><?php echo $alumno['turno'] ?: '<span class="empty-data">No asignado</span>'; ?></td>
                  <td>
                    <?php foreach ($condiciones as $condicion): 
                      $clase = strtolower(str_replace(' ', '-', $condicion));
                    ?>
                      <span class="condition-badge condition-<?php echo $clase; ?>"><?php echo $condicion; ?></span>
                    <?php endforeach; ?>
                    <?php if (empty($condiciones)) echo '<span class="empty-data">Ninguna</span>'; ?>
                  </td>
                  <td><?php echo ($alumno['representante_nombre'] && $alumno['representante_apellido']) ? 
                        $alumno['representante_nombre'] . ' ' . $alumno['representante_apellido'] : 
                        '<span class="empty-data">No registrado</span>'; ?></td>
                  <td><?php echo $alumno['representante_cedula'] ?: '<span class="empty-data">No registrada</span>'; ?></td>
                  <td><?php echo $alumno['representante_telefono'] ?: '<span class="empty-data">No registrado</span>'; ?></td>
                  <td><?php echo $alumno['representante_direccion'] ?: '<span class="empty-data">No registrada</span>'; ?></td>
                  <td><?php echo $alumno['profesion_representante'] ?: '<span class="empty-data">No registrada</span>'; ?></td>
                  <td><?php echo ($alumno['docente_nombre'] && $alumno['docente_apellido']) ? 
                        $alumno['docente_nombre'] . ' ' . $alumno['docente_apellido'] : 
                        '<span class="empty-data">No asignado</span>'; ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php 
            endforeach;
        else:
        ?>
          <div style="text-align: center; padding: 40px;">
            <h3>No hay alumnos registrados en el sistema</h3>
            <p>El informe general se mostrará cuando existan alumnos registrados.</p>
          </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin: 20px 0;">
          <button class="btn btn-print" onclick="window.print()">🖨️ Imprimir Reporte</button>
          <a href="?" class="btn btn-danger">← Volver a Estadísticas</a>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- NAVEGACIÓN INFERIOR - SIEMPRE VISIBLE -->
    <div class="bottom-nav">
      <a href="panel_alumnos.php" class="btn">👥 Ver Alumnos</a>
      <a href="formulario_inscripcion.php" class="btn btn-secondary">➕ Nuevo Alumno</a>
      <a href="panel_alumnos.php" class="btn">🏠 Menú Principal</a>
      <button onclick="window.print()" class="btn btn-print">🖨️ Imprimir</button>
      <!-- Botón para abrir el modal de cerrar sesión -->
      <button type="button" class="btn btn-danger" id="logoutBtn">🚪 Cerrar Sesión</button>
    </div>
  </div>

  <!-- MODAL DE CONFIRMACIÓN PARA CERRAR SESIÓN -->
  <div class="modal" id="logoutModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Confirmar Cierre de Sesión</h3>
      </div>
      <div class="modal-body">
        <p>¿Está seguro de que desea cerrar sesión?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-cancel" id="cancelLogout">Cancelar</button>
        <form action="logout.php" method="POST" style="display: inline;">
          <button type="submit" class="btn btn-confirm">Cerrar Sesión</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    // CONFIGURACIÓN DEL MODAL DE CERRAR SESIÓN
    document.addEventListener('DOMContentLoaded', function() {
      const logoutBtn = document.getElementById('logoutBtn');
      const logoutModal = document.getElementById('logoutModal');
      const cancelLogout = document.getElementById('cancelLogout');
      
      // Mostrar modal al hacer clic en Cerrar Sesión
      if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
          logoutModal.style.display = 'flex';
        });
      }
      
      // Ocultar modal al hacer clic en Cancelar
      if (cancelLogout) {
        cancelLogout.addEventListener('click', function() {
          logoutModal.style.display = 'none';
        });
      }
      
      // Ocultar modal al hacer clic fuera del contenido
      window.addEventListener('click', function(event) {
        if (event.target === logoutModal) {
          logoutModal.style.display = 'none';
        }
      });
      
      console.log('Sistema de estadísticas cargado correctamente');
    });
  </script>
</body>
</html>