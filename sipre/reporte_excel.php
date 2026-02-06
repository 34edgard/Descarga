<?php
// 1. INICIAR BUFFER PARA EVITAR ERRORES DE ESPACIOS EN BLANCO
ob_start();
session_start();
include 'includes/conexion.php';

// Limpiar cualquier salida previa (espacios, enter) antes de los headers
ob_end_clean();

if (!isset($_SESSION['usuario_id'])) {
    exit("Acceso denegado. Debe iniciar sesión.");
}

// 2. CONFIGURAR HEADERS PARA FORZAR DESCARGA EN EXCEL
$fecha_hoy = date('d-m-Y_His');
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Reporte_SIPRE_$fecha_hoy.xls");
header("Pragma: no-cache");
header("Expires: 0");

// BOM para que Excel reconozca tildes y caracteres especiales (UTF-8)
echo "\xEF\xBB\xBF";

// 3. CONSTRUCCIÓN DE LA CONSULTA (IGUAL QUE EN EL MÓDULO)
$where = "WHERE 1=1";
$subtitulo = "";

// Filtro Nivel
if (!empty($_GET['nivel'])) {
    $n = mysqli_real_escape_string($conexion, $_GET['nivel']);
    $where .= " AND a.nivel = '$n'";
    $subtitulo .= " | NIVEL: $n";
}

// Filtro Turno
if (!empty($_GET['turno'])) {
    $t = mysqli_real_escape_string($conexion, $_GET['turno']);
    $where .= " AND a.turno = '$t'";
    $subtitulo .= " | TURNO: $t";
}

// Filtro Sección (ID)
if (!empty($_GET['seccion'])) {
    $s = intval($_GET['seccion']);
    $where .= " AND a.seccion_id = $s";
    
    // Buscar la letra para ponerla bonita en el título
    $q_sec = mysqli_query($conexion, "SELECT letra, nivel FROM secciones WHERE id=$s");
    if($r_sec = mysqli_fetch_assoc($q_sec)) {
        $subtitulo .= " | SECCIÓN: {$r_sec['letra']}";
    }
}

// Filtro Mes Cumpleaños
if (!empty($_GET['mes'])) {
    $m = intval($_GET['mes']);
    $where .= " AND MONTH(a.fecha_nacimiento) = $m";
    $meses = ["", "Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
    $nombre_mes = $meses[$m] ?? $m;
    $subtitulo .= " | CUMPLEAÑEROS DE: " . strtoupper($nombre_mes);
}

// Filtro Condición Médica
if (!empty($_GET['condicion'])) {
    $c = mysqli_real_escape_string($conexion, $_GET['condicion']);
    if($c == 'CUALQUIERA') {
        $where .= " AND (a.condicion_medica IS NOT NULL AND a.condicion_medica != '' AND a.condicion_medica != 'Ninguna')";
        $subtitulo .= " | CON CONDICIÓN MÉDICA";
    } else {
        $where .= " AND a.condicion_medica LIKE '%$c%'";
        $subtitulo .= " | SALUD: $c";
    }
}

$tipo = $_GET['tipo_reporte'] ?? 'matricula';

// SQL PRINCIPAL
$sql = "SELECT a.*, 
        r.nombre as r_nombre, r.cedula as r_cedula, r.telefono as r_telefono, r.direccion_representante,
        s.letra as letra_seccion
        FROM alumnos a 
        LEFT JOIN representantes r ON a.representante_id = r.id 
        LEFT JOIN secciones s ON a.seccion_id = s.id 
        $where 
        ORDER BY a.nivel, s.letra, a.nombre ASC";

$query = mysqli_query($conexion, $sql);
?>

<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="background-color: #667eea; color: white; height: 40px; font-size: 16px; vertical-align: middle; text-align: center;">
                REPORTE SIPRE: <?php echo strtoupper($tipo); ?> <?php echo $subtitulo; ?>
            </th>
        </tr>
        <tr style="background-color: #eeeeee; font-weight: bold; text-align: center;">
            <th style="width: 120px;">CÉDULA ESCOLAR</th>
            <th style="width: 250px;">NOMBRE ALUMNO</th>
            <th style="width: 80px;">NIVEL</th>
            <th style="width: 80px;">SECCIÓN</th>
            <th style="width: 80px;">TURNO</th>

            <?php if ($tipo == 'matricula'): ?>
                <th>EDAD</th>
                <th>SEXO</th>
                <th>PROCEDENCIA</th>
            <?php endif; ?>

            <?php if ($tipo == 'salud'): ?>
                <th style="background-color: #ffcdd2;">CONDICIÓN MÉDICA</th>
                <th>REPRESENTANTE</th>
                <th>TELÉFONO</th>
            <?php endif; ?>

            <?php if ($tipo == 'contacto'): ?>
                <th>NOMBRE REPRESENTANTE</th>
                <th>CÉDULA REP.</th>
                <th>TELÉFONO</th>
            <?php endif; ?>

            <?php if ($tipo == 'cumpleanos'): ?>
                <th>FECHA NACIMIENTO</th>
                <th>DÍA Y MES</th>
                <th>EDAD ACTUAL</th>
            <?php endif; ?>

            <?php if ($tipo == 'completo'): ?>
                <th>EDAD</th>
                <th>SEXO</th>
                <th>FECHA NAC.</th>
                <th>CONDICIÓN MÉDICA</th>
                <th>DIRECCIÓN</th>
                <th>NOMBRE REPRESENTANTE</th>
                <th>TELÉFONO</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($query)): 
            // Preparar datos
            $seccion = $row['letra_seccion'] ?? 'Sin Asignar';
            $fecha_nac = date("d/m/Y", strtotime($row['fecha_nacimiento']));
            $dia_mes = date("d - M", strtotime($row['fecha_nacimiento']));
        ?>
        <tr>
            <td style="text-align: left;">'<?php echo $row['cedula_escolar']; ?></td>
            <td><?php echo strtoupper($row['nombre']); ?></td>
            <td style="text-align: center;"><?php echo $row['nivel']; ?></td>
            <td style="text-align: center; font-weight: bold;"><?php echo $seccion; ?></td>
            <td style="text-align: center;"><?php echo $row['turno']; ?></td>

            <?php if ($tipo == 'matricula'): ?>
                <td style="text-align: center;"><?php echo $row['edad']; ?></td>
                <td style="text-align: center;"><?php echo $row['sexo']; ?></td>
                <td><?php echo $row['lugar_procedencia']; ?></td>
            <?php endif; ?>

            <?php if ($tipo == 'salud'): ?>
                <td style="color: red; font-weight: bold;"><?php echo $row['condicion_medica']; ?></td>
                <td><?php echo $row['r_nombre']; ?></td>
                <td style="text-align: left;">'<?php echo $row['r_telefono']; ?></td>
            <?php endif; ?>

            <?php if ($tipo == 'contacto'): ?>
                <td><?php echo $row['r_nombre']; ?></td>
                <td style="text-align: left;">'<?php echo $row['r_cedula']; ?></td>
                <td style="text-align: left;">'<?php echo $row['r_telefono']; ?></td>
            <?php endif; ?>

            <?php if ($tipo == 'cumpleanos'): ?>
                <td style="text-align: center;"><?php echo $fecha_nac; ?></td>
                <td style="text-align: center; background-color: #e3f2fd; font-weight: bold;"><?php echo $dia_mes; ?></td>
                <td style="text-align: center;"><?php echo $row['edad']; ?></td>
            <?php endif; ?>

            <?php if ($tipo == 'completo'): ?>
                <td style="text-align: center;"><?php echo $row['edad']; ?></td>
                <td style="text-align: center;"><?php echo $row['sexo']; ?></td>
                <td style="text-align: center;"><?php echo $fecha_nac; ?></td>
                <td style="color: red;"><?php echo $row['condicion_medica']; ?></td>
                <td><?php echo $row['direccion_nino']; ?></td>
                <td><?php echo $row['r_nombre']; ?></td>
                <td style="text-align: left;">'<?php echo $row['r_telefono']; ?></td>
            <?php endif; ?>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>