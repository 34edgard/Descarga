<?php
session_start();
include 'includes/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$where = "WHERE 1=1";
$filtros_url = "";

// VARIABLES PARA MANTENER LA SELECCIÓN EN EL HTML
$nivel_sel = $_POST['nivel'] ?? '';
$turno_sel = $_POST['turno'] ?? '';
$seccion_sel = $_POST['seccion'] ?? '';
$mes_sel = $_POST['mes'] ?? '';
$condicion_sel = $_POST['condicion'] ?? '';
$tipo_reporte = $_POST['tipo_reporte'] ?? 'matricula';

// --- 1. PROCESAR FILTROS ---
if (isset($_POST['filtrar'])) {
    
    if (!empty($nivel_sel)) {
        $n = mysqli_real_escape_string($conexion, $nivel_sel);
        $where .= " AND a.nivel = '$n'";
        $filtros_url .= "&nivel=$n";
    }
    if (!empty($turno_sel)) {
        $t = mysqli_real_escape_string($conexion, $turno_sel);
        $where .= " AND a.turno = '$t'";
        $filtros_url .= "&turno=$t";
    }
    // FILTRO SECCIÓN
    if (!empty($seccion_sel)) {
        $s = intval($seccion_sel);
        $where .= " AND a.seccion_id = $s";
        $filtros_url .= "&seccion=$s";
    }
    if (!empty($mes_sel)) {
        $m = intval($mes_sel);
        $where .= " AND MONTH(a.fecha_nacimiento) = $m";
        $filtros_url .= "&mes=$m";
    }
    if (!empty($condicion_sel)) {
        $c = mysqli_real_escape_string($conexion, $condicion_sel);
        if($c == 'CUALQUIERA') {
            $where .= " AND (a.condicion_medica IS NOT NULL AND a.condicion_medica != '' AND a.condicion_medica != 'Ninguna')";
        } else {
            $where .= " AND a.condicion_medica LIKE '%$c%'";
        }
        $filtros_url .= "&condicion=$c";
    }
    
    $filtros_url .= "&tipo_reporte=$tipo_reporte";
}

// --- 2. CONSULTA SQL ---
$sql = "SELECT a.*, 
        r.nombre as r_nombre, r.cedula as r_cedula, r.telefono as r_telefono,
        s.letra as letra_seccion, s.turno as turno_seccion
        FROM alumnos a 
        LEFT JOIN representantes r ON a.representante_id = r.id 
        LEFT JOIN secciones s ON a.seccion_id = s.id 
        $where 
        ORDER BY a.nivel, s.letra, a.nombre ASC";

$query = mysqli_query($conexion, $sql);
$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - SIPRE</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 0; padding: 20px; min-height: 100vh; }
        .panel { max-width: 1250px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #333; border-bottom: 2px solid #667eea; padding-bottom: 10px; margin-bottom: 25px; }
        
        .filtros-box { background: #f8f9fa; padding: 25px; border-radius: 12px; border: 1px solid #e9ecef; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end; margin-bottom: 25px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; color: #555; font-size: 13px; }
        select { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; }
        .select-tipo { border: 2px solid #667eea; background: #f0f3ff; color: #333; font-weight: bold; }

        .btn { padding: 10px 20px; border: none; border-radius: 6px; color: white; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; justify-content: center; font-size: 14px; transition: 0.2s; }
        .btn:hover { transform: translateY(-2px); opacity: 0.95; }
        .btn-filtrar { background: #667eea; } .btn-reset { background: #95a5a6; } .btn-excel { background: #27ae60; } .btn-back { background: #34495e; }

        .table-responsive { overflow-x: auto; border-radius: 8px; border: 1px solid #eee; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        th { background: #667eea; color: white; padding: 12px; font-size: 13px; text-transform: uppercase; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; color: #444; font-size: 14px; }
        tr:hover { background: #f1f1f1; }
        .badge-danger { background: #ffebee; color: #c62828; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }
    </style>
</head>
<body>

<div class="panel">
    <h2><i class="fas fa-print"></i> Configuración de Reportes</h2>

    <form method="POST" class="filtros-box">
        <div style="grid-column: span 2;">
            <label><i class="fas fa-list-alt"></i> Tipo de Reporte:</label>
            <select name="tipo_reporte" class="select-tipo">
                <option value="matricula" <?php if($tipo_reporte=='matricula') echo 'selected'; ?>>📝 Matrícula Simple</option>
                <option value="salud" <?php if($tipo_reporte=='salud') echo 'selected'; ?>>🏥 Salud / Condiciones</option>
                <option value="contacto" <?php if($tipo_reporte=='contacto') echo 'selected'; ?>>📞 Contacto (Padres)</option>
                <option value="completo" <?php if($tipo_reporte=='completo') echo 'selected'; ?>>📂 Expediente Completo</option>
                <option value="cumpleanos" <?php if($tipo_reporte=='cumpleanos') echo 'selected'; ?>>🎂 Cumpleañeros</option>
            </select>
        </div>

        <div>
            <label>Nivel:</label>
            <select name="nivel" id="nivel" onchange="cargarSeccionesDinamicas()">
                <option value="">-- Todos --</option>
                <option value="1°" <?php if($nivel_sel=='1°') echo 'selected'; ?>>1° NIVEL</option>
                <option value="2°" <?php if($nivel_sel=='2°') echo 'selected'; ?>>2° NIVEL</option>
                <option value="3°" <?php if($nivel_sel=='3°') echo 'selected'; ?>>3° NIVEL</option>
            </select>
        </div>

        <div>
            <label>Turno:</label>
            <select name="turno" id="turno" onchange="cargarSeccionesDinamicas()">
                <option value="">-- Todos --</option>
                <option value="Mañana" <?php if($turno_sel=='Mañana') echo 'selected'; ?>>MAÑANA</option>
                <option value="Tarde" <?php if($turno_sel=='Tarde') echo 'selected'; ?>>TARDE</option>
            </select>
        </div>

        <div>
            <label>Sección (Registradas):</label>
            <select name="seccion" id="seccion">
                <option value="">-- Todas --</option>
                </select>
        </div>

        <div>
            <label>Condición Médica:</label>
            <select name="condicion">
                <option value="">-- Todas --</option>
                <option value="CUALQUIERA" <?php if($condicion_sel=='CUALQUIERA') echo 'selected'; ?>>[ ! ] Alguna Condición</option>
                <option value="Asma" <?php if($condicion_sel=='Asma') echo 'selected'; ?>>Asma</option>
                <option value="Alergia" <?php if($condicion_sel=='Alergia') echo 'selected'; ?>>Alergia</option>
            </select>
        </div>

        <div>
            <label>Mes:</label>
            <select name="mes">
                <option value="">-- Todos --</option>
                <?php
                $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                foreach($meses as $k => $m) {
                    $val = $k+1;
                    $sel = ($mes_sel == $val) ? 'selected' : '';
                    echo "<option value='$val' $sel>$m</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" name="filtrar" class="btn btn-filtrar"><i class="fas fa-search"></i> Generar</button>
        <a href="modulo_reportes.php" class="btn btn-reset"><i class="fas fa-sync"></i></a>
    </form>

    <?php if($total > 0): ?>
        <div style="text-align: right; margin-bottom: 10px;">
            <a href="reporte_excel.php?excel=1<?php echo $filtros_url; ?>" target="_blank" class="btn btn-excel">
                <i class="fas fa-file-excel"></i> Descargar Excel
            </a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Alumno</th>
                        <th>Nivel</th>
                        <th>Sección</th>
                        
                        <?php if($tipo_reporte == 'salud'): ?>
                            <th>Condición Médica</th>
                            <th>Emergencia (Rep)</th>
                            <th>Teléfono</th>
                        <?php endif; ?>

                        <?php if($tipo_reporte == 'matricula' || $tipo_reporte == 'completo'): ?>
                            <th>Edad</th>
                            <th>Sexo</th>
                        <?php endif; ?>

                        <?php if($tipo_reporte == 'contacto'): ?>
                            <th>Representante</th>
                            <th>Teléfono</th>
                        <?php endif; ?>

                        <?php if($tipo_reporte == 'cumpleanos'): ?>
                            <th>Fecha Nac.</th>
                            <th>Día</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?php echo $row['cedula_escolar']; ?></td>
                        <td><strong><?php echo $row['nombre']; ?></strong></td>
                        <td><?php echo $row['nivel']; ?></td>
                        <td style="text-align:center; font-weight:bold;"><?php echo $row['letra_seccion'] ?? '-'; ?></td>

                        <?php if($tipo_reporte == 'salud'): ?>
                            <td>
                                <?php if(!empty($row['condicion_medica']) && $row['condicion_medica']!='Ninguna'): ?>
                                    <span class="badge-danger"><?php echo $row['condicion_medica']; ?></span>
                                <?php else: ?>
                                    Sano
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['r_nombre']; ?></td>
                            <td><?php echo $row['r_telefono']; ?></td>
                        <?php endif; ?>

                        <?php if($tipo_reporte == 'matricula' || $tipo_reporte == 'completo'): ?>
                            <td><?php echo $row['edad']; ?></td>
                            <td><?php echo $row['sexo']; ?></td>
                        <?php endif; ?>

                        <?php if($tipo_reporte == 'contacto'): ?>
                            <td><?php echo $row['r_nombre']; ?></td>
                            <td><?php echo $row['r_telefono']; ?></td>
                        <?php endif; ?>

                        <?php if($tipo_reporte == 'cumpleanos'): ?>
                            <td><?php echo date("d/m/Y", strtotime($row['fecha_nacimiento'])); ?></td>
                            <td style="color:#667eea; font-weight:bold;"><?php echo date("d - M", strtotime($row['fecha_nacimiento'])); ?></td>
                        <?php endif; ?>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align:center; color:#777; margin-top:20px;">No se encontraron registros con los filtros seleccionados.</p>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 30px;">
        <a href="panel_alumnos.php" class="btn btn-back">Volver al Panel</a>
    </div>
</div>

<script>
    // Variable para recordar la selección PHP al recargar
    const seccionPreseleccionada = "<?php echo $seccion_sel; ?>";

    function cargarSeccionesDinamicas() {
        const nivel = document.getElementById('nivel').value;
        const turno = document.getElementById('turno').value;
        const selectSeccion = document.getElementById('seccion');

        // Llamada AJAX
        fetch(`ajax_reporte_secciones.php?nivel=${nivel}&turno=${turno}`)
            .then(response => response.json())
            .then(data => {
                // Limpiar select
                selectSeccion.innerHTML = '<option value="">-- Todas --</option>';
                
                // Llenar con datos de la BD
                data.forEach(item => {
                    const isSelected = (item.id == seccionPreseleccionada) ? 'selected' : '';
                    selectSeccion.innerHTML += `<option value="${item.id}" ${isSelected}>${item.texto}</option>`;
                });
            })
            .catch(error => console.error('Error cargando secciones:', error));
    }

    // Cargar al inicio para que si ya hay filtros, se muestren las secciones correctas
    window.addEventListener('load', cargarSeccionesDinamicas);
</script>

</body>
</html>