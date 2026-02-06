<?php
session_start();
include 'includes/conexion.php';

// Validar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// --- 1. LÓGICA DE CIERRE DE AÑO (ZONA DE PELIGRO) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cerrar_anio_escolar'])) {
    $nuevo_periodo = mysqli_real_escape_string($conexion, $_POST['nuevo_periodo_confirmacion']);
    
    $sql_reset = "UPDATE alumnos SET 
                  estado_promocion = 'Activo', 
                  seccion_id = NULL, 
                  aula = NULL, 
                  ano_escolar = '$nuevo_periodo' 
                  WHERE estado_promocion IN ('Promovido', 'Reprobado')";
    
    if(mysqli_query($conexion, $sql_reset)) {
        $afectados = mysqli_affected_rows($conexion);
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Año Escolar Cerrado!',
                    html: 'Se han migrado <b>$afectados alumnos</b> al nuevo periodo <b>$nuevo_periodo</b>.',
                    icon: 'success'
                }).then(() => { window.location.href = 'panel_alumnos.php'; });
            });
        </script>";
    }
}

// --- 2. LÓGICA DE ACTUALIZACIÓN MASIVA ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion_masiva'])) {
    if (!empty($_POST['alumnos_seleccionados'])) {
        $ids = array_map('intval', $_POST['alumnos_seleccionados']);
        $lista_ids = implode(',', $ids);
        $accion = $_POST['tipo_accion']; 
        $sql_update = ""; // Inicializar variable para evitar error

        // Snapshot al historial
        $sql_snapshot = "SELECT id, nivel, ano_escolar, seccion_id FROM alumnos WHERE id IN ($lista_ids)";
        $res_snap = mysqli_query($conexion, $sql_snapshot);
        
        while($snap = mysqli_fetch_assoc($res_snap)) {
            $id_alum = $snap['id'];
            $nivel_act = $snap['nivel'];
            $ano_act = $snap['ano_escolar'];
            
            $sec_letra = 'U'; 
            if($snap['seccion_id'] > 0) {
                $q_sec = mysqli_query($conexion, "SELECT letra FROM secciones WHERE id=".$snap['seccion_id']);
                if($r_sec = mysqli_fetch_assoc($q_sec)) $sec_letra = $r_sec['letra'];
            }

            $estado_final = 'Promovido';
            if ($accion == 'repetir') $estado_final = 'Reprobado';
            if ($accion == 'retirar') $estado_final = 'Retirado';

            $sql_hist = "INSERT INTO historial_academico (alumno_id, ano_escolar, nivel, seccion, estado_final) 
                         VALUES ($id_alum, '$ano_act', '$nivel_act', '$sec_letra', '$estado_final')";
            @mysqli_query($conexion, $sql_hist);
        }

        // Definir consulta SQL según acción
        if ($accion == 'promover') {
            $sql_update = "UPDATE alumnos SET 
                             estado_promocion = 'Promovido',
                             nivel = CASE 
                                WHEN nivel = '1°' THEN '2°'
                                WHEN nivel = '2°' THEN '3°'
                                WHEN nivel = '3°' THEN '3°' 
                                ELSE nivel 
                             END
                             WHERE id IN ($lista_ids)";
            $msg = "✅ Alumnos promovidos correctamente.";
        } elseif ($accion == 'repetir') {
            $sql_update = "UPDATE alumnos SET estado_promocion = 'Reprobado' WHERE id IN ($lista_ids)";
            $msg = "⚠️ Alumnos marcados como Reprobados.";
        } elseif ($accion == 'retirar') {
            $sql_update = "UPDATE alumnos SET estado_promocion = 'Retirado' WHERE id IN ($lista_ids)";
            $msg = "🚫 Alumnos marcados como retirados.";
        }

        // Ejecutar solo si hay consulta definida (CORRECCIÓN CLAVE)
        if (!empty($sql_update)) {
            mysqli_query($conexion, $sql_update);
            echo "<script>alert('$msg'); window.location.href='promocion.php';</script>";
        } else {
            echo "<script>window.location.href='promocion.php';</script>";
        }
        exit;
    }
}

// --- 3. ACTUALIZACIÓN INDIVIDUAL ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_alumno'])) {
    $id_alumno = intval($_POST['alumno_id']);
    $nuevo_nivel_form = mysqli_real_escape_string($conexion, $_POST['nuevo_nivel']);
    $nuevo_estado = mysqli_real_escape_string($conexion, $_POST['nuevo_estado']);
    
    $sql_actual = "SELECT * FROM alumnos WHERE id=$id_alumno";
    $res_actual = mysqli_query($conexion, $sql_actual);
    $datos_actuales = mysqli_fetch_assoc($res_actual);
    $nivel_real = $datos_actuales['nivel'];
    $ano_actual = $datos_actuales['ano_escolar'];

    if ($nuevo_estado == 'Reprobado') {
        $nivel_final = $nivel_real; 
    } else {
        $nivel_final = $nuevo_nivel_form;
    }

    $estado_historial = ($nuevo_estado == 'Activo') ? 'En Curso' : $nuevo_estado;
    @mysqli_query($conexion, "INSERT INTO historial_academico (alumno_id, ano_escolar, nivel, estado_final) 
                              VALUES ($id_alumno, '$ano_actual', '$nivel_real', '$estado_historial')");

    $sql_update = "UPDATE alumnos SET nivel = '$nivel_final', estado_promocion = '$nuevo_estado' WHERE id = $id_alumno";
    
    if(mysqli_query($conexion, $sql_update)){
        echo "<script>alert('✅ Cambio aplicado correctamente'); window.location.href='promocion.php';</script>";
    } else {
        echo "<script>alert('Error al guardar');</script>";
    }
}

// --- 4. CONSULTAS Y FILTROS ---
$sql_secciones = "SELECT * FROM secciones ORDER BY letra ASC";
$res_secciones = mysqli_query($conexion, $sql_secciones);

$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

$where = "WHERE 1=1";
$busqueda = isset($_GET['busqueda']) ? mysqli_real_escape_string($conexion, $_GET['busqueda']) : "";
$filtro_nivel = isset($_GET['filtro_nivel']) ? mysqli_real_escape_string($conexion, $_GET['filtro_nivel']) : "";
$filtro_estado = isset($_GET['filtro_estado']) ? mysqli_real_escape_string($conexion, $_GET['filtro_estado']) : "";
$filtro_seccion = isset($_GET['filtro_seccion']) ? mysqli_real_escape_string($conexion, $_GET['filtro_seccion']) : "";

if (!empty($busqueda)) $where .= " AND (a.nombre LIKE '%$busqueda%' OR a.cedula_escolar LIKE '%$busqueda%')";
if (!empty($filtro_nivel)) $where .= " AND a.nivel = '$filtro_nivel'";
if (!empty($filtro_estado)) $where .= " AND a.estado_promocion = '$filtro_estado'";
if (!empty($filtro_seccion)) $where .= " AND a.seccion_id = '$filtro_seccion'";

$sql_count = "SELECT COUNT(*) as total FROM alumnos a $where";
$res_count = mysqli_query($conexion, $sql_count);
$row_count = mysqli_fetch_assoc($res_count);
$total_registros = $row_count['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

$sql_alumnos = "SELECT a.*, s.letra as letra_seccion 
                FROM alumnos a 
                LEFT JOIN secciones s ON a.seccion_id = s.id 
                $where 
                ORDER BY a.nivel ASC, a.nombre ASC 
                LIMIT $offset, $registros_por_pagina";
$query = mysqli_query($conexion, $sql_alumnos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Promoción - SIPRE</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; --success: #28a745; --danger: #e74c3c; --warning: #f39c12; --info: #3498db; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); min-height: 100vh; padding: 30px 20px; color: #333; }
        .container { max-width: 1150px; margin: 0 auto; background-color: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); padding: 30px; animation: fadeIn 0.5s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .institution-header { text-align: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0; display: flex; align-items: center; justify-content: center; gap: 20px; }
        .institution-logo { width: 80px; }
        .institution-name { color: #2c3e50; font-size: 22px; font-weight: bold; }
        .system-title { color: var(--primary); font-size: 16px; font-weight: 600; letter-spacing: 1px; }
        
        .top-navigation { display: flex; justify-content: space-between; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .top-nav-btn { background: #6c757d; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .top-nav-btn:hover { background: #5a6268; transform: translateY(-2px); }
        .logout-btn { background: #dc3545; } .logout-btn:hover { background: #c82333; }
        h2 { color: #444; font-size: 22px; margin: 20px 0 15px; border-left: 5px solid var(--primary); padding-left: 15px; }

        .search-bar { background: #f8f9fa; padding: 15px; border-radius: 10px; display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; border: 1px solid #e9ecef; }
        .search-input { flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
        .filter-select { padding: 10px; border: 1px solid #ccc; border-radius: 6px; min-width: 120px; }
        .btn-filter { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
        .btn-reset { background: #95a5a6; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; }
        .btn-history { background: #6c5ce7; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: bold; }

        .bulk-actions { display: none; background: #e8f4fd; border: 1px solid #b3e5fc; color: #0c5460; padding: 15px; margin-bottom: 15px; border-radius: 8px; justify-content: space-between; align-items: center; }
        .bulk-buttons { display: flex; gap: 10px; }
        .btn-promote { background: var(--success); color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-repeat { background: var(--warning); color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-retire { background: var(--danger); color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; }

        .student-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .student-table th { background: var(--primary); color: white; padding: 12px 15px; text-align: left; }
        .student-table td { padding: 10px 15px; border-bottom: 1px solid #e0e0e0; }
        .student-table tr:hover { background: #f1f8ff; }

        .pagination { display: flex; justify-content: center; gap: 5px; margin-top: 30px; }
        .page-link { padding: 8px 12px; border: 1px solid #ddd; color: var(--primary); text-decoration: none; border-radius: 4px; background: white; }
        .page-link.active { background: var(--primary); color: white; border-color: var(--primary); }
        
        .level-select, .status-select { padding: 6px; border-radius: 4px; border: 1px solid #ddd; width: 100%; }
        .action-btn-single { background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; }
        .big-checkbox { transform: scale(1.3); cursor: pointer; }

        .print-section { text-align: center; margin-top: 30px; padding: 25px; background: #f8f9fa; border-radius: 12px; border: 2px dashed #ccc; }
        .print-btn { background: var(--info); color: white; border: none; padding: 12px 30px; border-radius: 50px; font-size: 16px; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .print-btn:disabled { background: #bdc3c7; cursor: not-allowed; box-shadow: none; }

        .danger-zone { margin-top: 50px; border: 2px solid #e74c3c; border-radius: 15px; padding: 20px; text-align: center; background-color: #fdf2f2; }
        .danger-title { color: #c0392b; font-weight: bold; font-size: 18px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-danger-action { background: #e74c3c; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 15px; transition: 0.3s; }
        .btn-danger-action:hover { background: #c0392b; transform: scale(1.05); }

        .certificate-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 1000; overflow-y: auto; }
        .certificate-content { background: white; margin: 30px auto; width: 850px; border-radius: 10px; padding: 0; }
        .certificate-body { padding: 60px; text-align: center; font-family: 'Times New Roman', serif; position: relative; }
        .official-title { font-size: 28px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .official-subtitle { font-size: 16px; margin-bottom: 40px; text-transform: uppercase; font-weight: bold; letter-spacing: 2px; }
        .official-text { font-size: 18px; line-height: 2.2; text-align: justify; margin-bottom: 40px; }
        .directors-container { display: flex; justify-content: space-between; margin-top: 80px; padding: 0 40px; }
        .director-section { text-align: center; width: 40%; font-size: 12px; }
        .signature-line { border-top: 1px solid #000; width: 100%; margin: 60px auto 10px; }
        .modal-actions { padding: 20px; background: #f1f1f1; border-radius: 0 0 10px 10px; display: flex; justify-content: center; gap: 15px; }
        .ministerio-logo-certificate { width: 120px; margin-bottom: 25px; }

        @media print {
            body { background: white; padding: 0; }
            .container, .print-section, .top-navigation, .search-bar, h2, .danger-zone { display: none; }
            .certificate-modal { position: static; background: white; width: 100%; }
            .certificate-content { margin: 0; width: 100%; box-shadow: none; }
            .modal-actions { display: none; }
        }
        @media (max-width: 768px) {
            .top-navigation, .search-bar, .bulk-actions { flex-direction: column; gap: 10px; }
            .student-table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="institution-header">
        <div class="logo-container"><img src="img/logo.png" alt="Logo" class="institution-logo"></div>
        <div class="institution-info">
            <div class="institution-name">COLEGIO "JOSÉ AGUSTÍN MÉNDEZ GARCÍA"</div>
            <div class="system-title">SIPRE-URUGUAY | MÓDULO DE PROMOCIÓN</div>
        </div>
    </div>
    
    <div class="top-navigation">
        <a href="panel_alumnos.php" class="top-nav-btn">← Menú principal</a>
        <a href="promocion.php" class="top-nav-btn">Actualizar Panel</a>
        <a href="logout.php" class="top-nav-btn logout-btn">Cerrar sesión</a>
    </div>

    <h2>Listado General de Alumnos</h2>

    <form method="GET" class="search-bar">
        <input type="text" name="busqueda" class="search-input" placeholder="Buscar por Nombre o Cédula..." value="<?php echo htmlspecialchars($busqueda); ?>">
        
        <select name="filtro_nivel" class="filter-select">
            <option value="">- Nivel -</option>
            <option value="1°" <?php if($filtro_nivel=='1°') echo 'selected'; ?>>1° Nivel</option>
            <option value="2°" <?php if($filtro_nivel=='2°') echo 'selected'; ?>>2° Nivel</option>
            <option value="3°" <?php if($filtro_nivel=='3°') echo 'selected'; ?>>3° Nivel</option>
        </select>

        <select name="filtro_seccion" class="filter-select">
            <option value="">- Sección -</option>
            <?php 
            if(mysqli_num_rows($res_secciones) > 0){
                while($sec = mysqli_fetch_assoc($res_secciones)){
                    $selected = ($filtro_seccion == $sec['id']) ? 'selected' : '';
                    echo "<option value='".$sec['id']."' $selected>Sección ".$sec['letra']."</option>";
                }
            }
            ?>
        </select>

        <select name="filtro_estado" class="filter-select">
            <option value="">- Estado -</option>
            <option value="Activo" <?php if($filtro_estado=='Activo') echo 'selected'; ?>>En Curso</option>
            <option value="Promovido" <?php if($filtro_estado=='Promovido') echo 'selected'; ?>>Promovidos</option>
            <option value="Reprobado" <?php if($filtro_estado=='Reprobado') echo 'selected'; ?>>Reprobados</option>
            <option value="Retirado" <?php if($filtro_estado=='Retirado') echo 'selected'; ?>>Retirados</option>
        </select>
        
        <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Buscar</button>
        <?php if($busqueda || $filtro_nivel || $filtro_estado || $filtro_seccion): ?>
            <a href="promocion.php" class="btn-reset"><i class="fas fa-times"></i> Limpiar</a>
        <?php endif; ?>
        
        <a href="reporte_sabana.php" target="_blank" class="btn-history"><i class="fas fa-file-pdf"></i> Historial Académico</a>
    </form>

    <form method="POST" id="formMasivo" style="display:none;"></form>

    <div class="bulk-actions" id="bulkBar">
        <div><i class="fas fa-check-circle"></i> <strong><span id="countSelected">0</span></strong> alumnos seleccionados</div>
        <div class="bulk-buttons">
            <input type="hidden" name="tipo_accion" id="tipoAccion" form="formMasivo">
            <input type="hidden" name="accion_masiva" value="1" form="formMasivo">
            
            <button type="button" onclick="submitMasivo('promover')" class="btn-promote"><i class="fas fa-arrow-up"></i> Promover</button>
            <button type="button" onclick="submitMasivo('repetir')" class="btn-repeat"><i class="fas fa-redo"></i> Repetir</button>
            <button type="button" onclick="submitMasivo('retirar')" class="btn-retire"><i class="fas fa-user-times"></i> Retirar</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="student-table">
            <thead>
                <tr>
                    <th width="40"><input type="checkbox" id="checkAll" class="big-checkbox" onchange="toggleAll(this)"></th>
                    <th>Nombre del Alumno</th>
                    <th>Cédula Escolar</th>
                    <th>Nivel</th>
                    <th>Sección</th> <th>Estado</th>
                    <th>Acción Indiv.</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($query) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($query)): 
                        $es_graduando = ($row['nivel'] == '3°' && $row['estado_promocion'] == 'Promovido');
                        $nombre = htmlspecialchars($row['nombre']);
                        $cedula = $row['cedula_escolar'];
                        $nacimiento = date("d/m/Y", strtotime($row['fecha_nacimiento']));
                        $municipio = htmlspecialchars($row['municipio']);
                        $estado = htmlspecialchars($row['estado']);
                        $anio = $row['ano_escolar'];
                        $letra_seccion = !empty($row['letra_seccion']) ? $row['letra_seccion'] : '-';
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="alumnos_seleccionados[]" form="formMasivo" value="<?php echo $row['id']; ?>" class="item-check big-checkbox" onchange="updateCounterAndCert(this)" data-graduando="<?php echo $es_graduando ? '1' : '0'; ?>" data-nombre="<?php echo $nombre; ?>" data-cedula="<?php echo $cedula; ?>" data-nacimiento="<?php echo $nacimiento; ?>" data-municipio="<?php echo $municipio; ?>" data-estado="<?php echo $estado; ?>" data-anio="<?php echo $anio; ?>">
                        </td>
                        <td><?php echo $nombre; ?></td>
                        <td><?php echo $cedula; ?></td>
                        
                        <form method="POST">
                            <input type="hidden" name="alumno_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="actualizar_alumno" value="1">
                            <td>
                                <select name="nuevo_nivel" class="level-select">
                                    <option value="1°" <?php if($row['nivel']=='1°') echo 'selected'; ?>>1°</option>
                                    <option value="2°" <?php if($row['nivel']=='2°') echo 'selected'; ?>>2°</option>
                                    <option value="3°" <?php if($row['nivel']=='3°') echo 'selected'; ?>>3°</option>
                                </select>
                            </td>
                            <td style="text-align:center; font-weight:bold; color:var(--info);">
                                <?php echo $letra_seccion; ?>
                            </td>
                            <td>
                                <select name="nuevo_estado" class="status-select">
                                    <option value="Activo" <?php if($row['estado_promocion']=='Activo') echo 'selected'; ?>>En curso</option>
                                    <option value="Promovido" <?php if($row['estado_promocion']=='Promovido') echo 'selected'; ?>>Promovido</option>
                                    <option value="Reprobado" <?php if($row['estado_promocion']=='Reprobado') echo 'selected'; ?>>Reprobado</option>
                                    <option value="Retirado" <?php if($row['estado_promocion']=='Retirado') echo 'selected'; ?>>Retirado</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="action-btn-single">Aplicar</button>
                            </td>
                        </form>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; padding:20px; color:#777;">No se encontraron resultados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($total_paginas > 1): ?>
    <div class="pagination">
        <?php 
        $url_base = "promocion.php?busqueda=$busqueda&filtro_nivel=$filtro_nivel&filtro_seccion=$filtro_seccion&filtro_estado=$filtro_estado&pag=";
        if($pagina_actual > 1) echo "<a href='{$url_base}".($pagina_actual-1)."' class='page-link'>&laquo; Anterior</a>";
        for($i=1; $i<=$total_paginas; $i++){
            $active = ($i == $pagina_actual) ? 'active' : '';
            if ($i == 1 || $i == $total_paginas || ($i >= $pagina_actual - 2 && $i <= $pagina_actual + 2)) {
                echo "<a href='{$url_base}$i' class='page-link $active'>$i</a>";
            } elseif ($i == $pagina_actual - 3 || $i == $pagina_actual + 3) echo "<span style='padding:5px;'>...</span>";
        }
        if($pagina_actual < $total_paginas) echo "<a href='{$url_base}".($pagina_actual+1)."' class='page-link'>Siguiente &raquo;</a>";
        ?>
    </div>
    <?php endif; ?>

    <div id="print-area" class="print-section">
        <h3 style="color:#555; margin-bottom:10px;">Zona de Impresión</h3>
        <div id="msg-select" style="margin-bottom:20px; color:#777;">
            <i class="fas fa-arrow-up"></i> Seleccione un alumno (3° Nivel Promovido) para habilitar el certificado.
        </div>
        <button class="print-btn" id="printCertificatesBtn" disabled onclick="openCertificateModal()">
            <i class="fas fa-file-contract fa-lg"></i> GENERAR CERTIFICADO OFICIAL
        </button>
    </div>

    <div class="danger-zone">
        <div class="danger-title"><i class="fas fa-exclamation-triangle"></i> CIERRE DE AÑO ESCOLAR</div>
        <p>Esta acción reiniciará los estados "Promovido" y "Reprobado" a "Activo".<br>También limpiará las asignaciones de secciones. <strong>¡Solo usar al final del año!</strong></p>
        <form method="POST" id="formCierre">
            <input type="hidden" name="cerrar_anio_escolar" value="1">
            <input type="hidden" name="nuevo_periodo_confirmacion" id="inputNuevoPeriodo">
            <button type="button" onclick="confirmarCierre()" class="btn-danger-action">INICIAR NUEVO AÑO ESCOLAR</button>
        </form>
    </div>
</div>

<div id="certificateModal" class="certificate-modal">
    <div class="certificate-content">
        <div class="certificate-body">
            <div class="logo-certificate-container" style="border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px;">
                <img src="img/ministerioe.png" alt="Ministerio" class="ministerio-logo-certificate" onerror="this.style.display='none'">
            </div>
            <div class="official-certificate">
                <div class="official-title">CERTIFICACIÓN DE ESTUDIOS</div>
                <div class="official-subtitle">DEL NIVEL DE EDUCACIÓN INICIAL</div>
                <div class="official-text">
                    <p>Quien suscribe, <strong>Profa. Cruz María Calzadilla</strong>, titular de la Cédula de Identidad N° <strong>10.304.844</strong> en su condición de Director(a) del P.E. <strong>José Agustín Méndez García</strong>, ubicado en el municipio <strong>Maturín</strong>, de la parroquia <strong>San Simón</strong>, adscrita al Centro de Desarrollo de la Calidad Educativa Estadal Monagas.</p>
                    <p>Por la presente certifica que el niño(a) <strong id="certName" style="text-transform: uppercase;"></strong> portador de la Cédula Escolar Nº o Pasaporte <strong id="certCedula"></strong>, nacido (a) en el Municipio <strong id="certMun"></strong> del Estado <strong id="certEst"></strong>, en fecha <strong id="certNac"></strong>, cursó el <strong>III Grupo</strong> de la Etapa Preescolar del Nivel de Educación Inicial durante el periodo escolar <strong id="certAnio"></strong> y continuará estudios en el <strong>1er. Grado</strong> del Nivel de Educación Primaria, previo cumplimiento de los requisitos exigidos en la normativa legal vigente.</p>
                    <p>Certificado que se expide en <strong>Maturín</strong>, a los <strong id="certDia"></strong> días del mes de <strong id="certMes"></strong> de <strong id="certYear"></strong>.</p>
                </div>
                <div class="directors-container">
                    <div class="director-section">
                        <p style="font-size:10px; margin-bottom:5px;">PARA VALIDEZ A NIVEL NACIONAL</p>
                        <p><strong>DIRECTOR(A)</strong></p>
                        <div class="signature-line"></div>
                        <p><strong>CRUZ MARÍA CALZADILLA</strong></p>
                        <p>C.I: 10.304.844</p>
                    </div>
                    <div class="director-section">
                        <p style="font-size:10px; margin-bottom:5px;">PARA VALIDEZ A NIVEL INTERNACIONAL</p>
                        <p><strong>DIRECTOR(A)</strong></p>
                        <div class="signature-line"></div>
                        <p><strong>CAROLINA ESTABA</strong></p>
                        <p>C.I: 13.263.844</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="top-nav-btn" onclick="document.getElementById('certificateModal').style.display='none'">Cerrar</button>
            <button class="print-btn" onclick="window.print()" style="padding:10px 20px;">Imprimir</button>
        </div>
    </div>
</div>

<script>
    const checkAll = document.getElementById('checkAll');
    const bulkBar = document.getElementById('bulkBar');
    const countSpan = document.getElementById('countSelected');
    const form = document.getElementById('formMasivo');
    const tipoAccionInput = document.getElementById('tipoAccion');
    
    let selectedStudentForCert = null;
    const btnCert = document.getElementById('printCertificatesBtn');
    const msgCert = document.getElementById('msg-select');

    function toggleAll(source) {
        document.querySelectorAll('.item-check').forEach(cb => {
            cb.checked = source.checked;
            handleCertLogic(cb);
        });
        updateCounter();
    }

    function updateCounterAndCert(checkbox) {
        handleCertLogic(checkbox);
        updateCounter();
    }

    function updateCounter() {
        const selected = document.querySelectorAll('.item-check:checked').length;
        countSpan.textContent = selected;
        bulkBar.style.display = selected > 0 ? 'flex' : 'none';
        if (selected === 0) checkAll.checked = false;
    }

    function handleCertLogic(checkbox) {
        const selectedBoxes = document.querySelectorAll('.item-check:checked');
        if (selectedBoxes.length === 1) {
            const cb = selectedBoxes[0];
            const esGraduando = cb.getAttribute('data-graduando') === '1';
            if(esGraduando) {
                btnCert.disabled = false;
                msgCert.style.display = "none";
                selectedStudentForCert = {
                    nombre: cb.dataset.nombre,
                    cedula: cb.dataset.cedula,
                    nacimiento: cb.dataset.nacimiento,
                    municipio: cb.dataset.municipio,
                    estado: cb.dataset.estado,
                    anio: cb.dataset.anio
                };
            } else disableCertBtn();
        } else disableCertBtn();
    }

    function disableCertBtn() {
        btnCert.disabled = true;
        msgCert.style.display = "block";
        selectedStudentForCert = null;
    }

    function submitMasivo(accion) {
        const selected = document.querySelectorAll('.item-check:checked').length;
        if(selected === 0) return;
        let mensaje = "";
        if(accion === 'promover') mensaje = `¿Confirma PROMOVER a estos ${selected} alumnos?\n(Se guardará historial)`;
        else if(accion === 'repetir') mensaje = `¿Confirma marcar como REPROBADOS a estos ${selected} alumnos?\n(Mantendrán su nivel)`;
        else mensaje = `¿Confirma RETIRAR a estos ${selected} alumnos?`;

        if(confirm(mensaje)) {
            tipoAccionInput.value = accion;
            form.submit();
        }
    }

    function confirmingCierre() {
        Swal.fire({
            title: '⚠️ ¿CERRAR AÑO ESCOLAR? ⚠️',
            html: '<p style="text-align:left">Esta acción es irreversible...</p>',
            icon: 'warning',
            input: 'text',
            inputLabel: 'Escriba el NUEVO año escolar (Ej: 2026-2027):',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            preConfirm: (value) => {
                if (!value) Swal.showValidationMessage('¡Debe escribir el nuevo año escolar!')
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('inputNuevoPeriodo').value = result.value;
                document.getElementById('formCierre').submit();
            }
        })
    }
    
    window.confirmarCierre = confirmingCierre;

    function openCertificateModal() {
        if(!selectedStudentForCert) return;
        const partesFecha = selectedStudentForCert.nacimiento.split('/'); 
        const fechaObj = new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0]);
        const opcionesFecha = { year: 'numeric', month: 'long', day: 'numeric' };
        const fechaNacTexto = fechaObj.toLocaleDateString('es-ES', opcionesFecha);
        const hoy = new Date();
        document.getElementById('certName').textContent = selectedStudentForCert.nombre;
        document.getElementById('certCedula').textContent = selectedStudentForCert.cedula;
        document.getElementById('certMun').textContent = selectedStudentForCert.municipio;
        document.getElementById('certEst').textContent = selectedStudentForCert.estado;
        document.getElementById('certNac').textContent = fechaNacTexto;
        document.getElementById('certAnio').textContent = selectedStudentForCert.anio;
        document.getElementById('certDia').textContent = hoy.getDate();
        document.getElementById('certMes').textContent = hoy.toLocaleDateString('es-ES', { month: 'long' }).toUpperCase();
        document.getElementById('certYear').textContent = hoy.getFullYear();
        document.getElementById('certificateModal').style.display = 'block';
    }
</script>
</body>
</html>