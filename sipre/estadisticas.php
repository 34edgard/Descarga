<?php
session_start();
include 'includes/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';

// --- 1. CONSULTAS KPI ---
$total_alumnos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM alumnos"))['t'];
$total_docentes = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM docentes"))['t'];
$total_varones = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM alumnos WHERE sexo = 'M' OR sexo = 'Masculino'"))['t'];
$total_hembras = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as t FROM alumnos WHERE sexo = 'F' OR sexo = 'Femenino'"))['t'];

// --- 2. PREPARACIÓN DE DATOS ---

// A. SEXO
$colores_sexo = ['#3498db', '#e91e63']; 
$datos_sexo = [
    ['label' => 'Varones', 'valor' => $total_varones, 'color' => $colores_sexo[0]],
    ['label' => 'Hembras', 'valor' => $total_hembras, 'color' => $colores_sexo[1]]
];

// B. NIVEL
$datos_nivel = [];
$q_niv = mysqli_query($conexion, "SELECT nivel, COUNT(*) as c FROM alumnos GROUP BY nivel ORDER BY nivel");
while($r = mysqli_fetch_assoc($q_niv)) { 
    $lbl = empty($r['nivel']) ? 'Sin Nivel' : $r['nivel'];
    $datos_nivel[] = ['label' => $lbl, 'valor' => $r['c']];
}

// C. TURNO
$conteo_turnos = ['Mañana' => 0, 'Tarde' => 0];
$q_tur = mysqli_query($conexion, "SELECT turno, COUNT(*) as c FROM alumnos GROUP BY turno");
while($r = mysqli_fetch_assoc($q_tur)) {
    $t = ucfirst(strtolower(trim($r['turno'])));
    if ($t == 'Mañana') $conteo_turnos['Mañana'] += $r['c'];
    elseif ($t == 'Tarde') $conteo_turnos['Tarde'] += $r['c'];
}
$colores_turno = ['#f1c40f', '#2c3e50'];
$datos_turno = [
    ['label' => 'Mañana', 'valor' => $conteo_turnos['Mañana'], 'color' => $colores_turno[0]],
    ['label' => 'Tarde', 'valor' => $conteo_turnos['Tarde'], 'color' => $colores_turno[1]]
];

// D. EDAD
$datos_edad = [];
$q_edad = mysqli_query($conexion, "SELECT edad, COUNT(*) as c FROM alumnos WHERE edad > 0 GROUP BY edad ORDER BY edad");
while($r = mysqli_fetch_assoc($q_edad)) { 
    $datos_edad[] = ['label' => $r['edad'] . " Años", 'valor' => $r['c']]; 
}

// Helper Leyenda
function generarLeyenda($datos, $total_base, $usar_color_array = true) {
    echo '<div class="legend-box">';
    foreach($datos as $d) {
        $color = $usar_color_array ? $d['color'] : '#667eea';
        $porcentaje = ($total_base > 0) ? round(($d['valor'] / $total_base) * 100, 1) : 0;
        echo '<div class="legend-item">
                <span class="color-dot" style="background-color: '.$color.';"></span>
                <span class="legend-text">'.$d['label'].'</span>
                <span class="legend-val">'.$d['valor'].'</span>
                <span class="legend-perc">('.$porcentaje.'%)</span>
              </div>';
    }
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas - SIPRE</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); margin: 0; padding: 20px; min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; }
        
        /* HEADER */
        .header { background: white; padding: 15px 25px; border-radius: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 25px; }
        .header h1 { margin: 0; color: #333; font-size: 1.5rem; display: flex; align-items: center; gap: 10px; }
        
        /* KPI GRID */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .kpi-card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; }
        .kpi-number { font-size: 2rem; font-weight: bold; color: #333; margin: 0; }
        .kpi-label { color: #777; font-size: 0.85rem; text-transform: uppercase; }
        
        /* CHART GRID */
        .charts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; margin-bottom: 30px; }
        .chart-card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .chart-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .chart-header h3 { margin: 0; color: #555; font-size: 1.1rem; }
        
        /* Contenedor del gráfico en pantalla */
        .chart-container-div { position: relative; height: 250px; width: 100%; }

        /* LEYENDA */
        .legend-box { margin-top: 15px; border-top: 1px solid #f0f0f0; padding-top: 10px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px; }
        .legend-item { display: flex; align-items: center; font-size: 0.85rem; color: #555; }
        .color-dot { width: 12px; height: 12px; border-radius: 3px; margin-right: 8px; }
        .legend-text { flex: 1; }
        .legend-val { font-weight: bold; margin-right: 5px; }

        /* BOTONES */
        .btn-mini { background: #f0f0f0; border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 0.8rem; }
        .nav-bar { background: white; padding: 15px; border-radius: 15px; text-align: center; display: flex; justify-content: center; gap: 15px; }
        .btn { padding: 12px 25px; border-radius: 8px; text-decoration: none; color: white; font-weight: bold; border: none; cursor: pointer; display: inline-block; }
        .btn-home { background: #667eea; } .btn-reports { background: #34495e; } .btn-print-all { background: #e67e22; }

        /* ==========================================================================
           CONFIGURACIÓN DE IMPRESIÓN (SOLUCIÓN DEFINITIVA)
           ========================================================================== */
        @media print {
            @page { margin: 0.5cm; size: auto; }
            body { background: white !important; margin: 0; padding: 0; -webkit-print-color-adjust: exact; }
            
            /* Ocultar navegación y botones SIEMPRE al imprimir */
            .nav-bar, .btn-mini, .btn-print-all { display: none !important; }

            /* ============================================
               MODO 1: IMPRESIÓN INDIVIDUAL (Solo 1 gráfico)
               ============================================ */
            
            /* 1. Ocultar TODO el contenido principal */
            body.printing-single .header,
            body.printing-single .kpi-grid,
            body.printing-single .charts-grid .chart-card:not(.print-target) {
                display: none !important; 
            }

            /* 2. Forzar al contenedor principal a ocupar todo */
            body.printing-single .container {
                width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important;
            }

            /* 3. Asegurar que el grid de gráficos no estorbe */
            body.printing-single .charts-grid {
                display: block !important; margin: 0 !important; padding: 0 !important; border: none !important;
            }

            /* 4. ESTILO DE LA TARJETA SELECCIONADA (La única visible) */
            body.printing-single .print-target {
                display: block !important;
                position: absolute;
                top: 0;
                left: 0;
                width: 100% !important;
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 20px !important;
            }

            /* 5. Hacer el gráfico GRANDE en la hoja */
            body.printing-single .print-target .chart-container-div {
                height: 500px !important; /* Alto fijo grande */
                width: 100% !important;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            body.printing-single .print-target canvas {
                max-width: 100% !important; max-height: 100% !important;
            }

            /* ============================================
               MODO 2: IMPRESIÓN TOTAL (Todo el reporte)
               ============================================ */
            body:not(.printing-single) .header {
                box-shadow: none; border-bottom: 2px solid #333; margin-bottom: 20px;
            }
            body:not(.printing-single) .header div { display: none; } /* Ocultar usuario */
            
            body:not(.printing-single) .kpi-grid {
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 10px !important;
            }
            body:not(.printing-single) .kpi-card {
                border: 1px solid #ccc; padding: 10px; box-shadow: none;
            }
            
            body:not(.printing-single) .charts-grid {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 20px !important;
            }
            body:not(.printing-single) .chart-card {
                border: 1px solid #ccc; box-shadow: none; page-break-inside: avoid;
            }
            body:not(.printing-single) .chart-container-div {
                height: 250px !important;
            }
        }
        
        @media (max-width: 768px) {
            .kpi-grid, .charts-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1><i class="fas fa-chart-pie"></i> Estadísticas</h1>
        <div><span>Usuario: <?php echo $nombre_usuario; ?></span></div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <p class="kpi-number"><?php echo $total_alumnos; ?></p>
            <span class="kpi-label">Total Alumnos</span>
        </div>
        <div class="kpi-card">
            <p class="kpi-number" style="color:#3498db;"><?php echo $total_varones; ?></p>
            <span class="kpi-label">Niños</span>
        </div>
        <div class="kpi-card">
            <p class="kpi-number" style="color:#e91e63;"><?php echo $total_hembras; ?></p>
            <span class="kpi-label">Niñas</span>
        </div>
        <div class="kpi-card">
            <p class="kpi-number"><?php echo $total_docentes; ?></p>
            <span class="kpi-label">Docentes</span>
        </div>
    </div>

    <div class="charts-grid">
        
        <div class="chart-card" id="card-sexo">
            <div class="chart-header">
                <h3>Género</h3>
                <button class="btn-mini" onclick="printSpecific('card-sexo')"><i class="fas fa-print"></i> Imprimir</button>
            </div>
            <div class="chart-container-div">
                <canvas id="chartSexo"></canvas>
            </div>
            <?php generarLeyenda($datos_sexo, $total_alumnos); ?>
        </div>

        <div class="chart-card" id="card-turno">
            <div class="chart-header">
                <h3>Turno</h3>
                <button class="btn-mini" onclick="printSpecific('card-turno')"><i class="fas fa-print"></i> Imprimir</button>
            </div>
            <div class="chart-container-div">
                <canvas id="chartTurno"></canvas>
            </div>
            <?php generarLeyenda($datos_turno, $total_alumnos); ?>
        </div>

        <div class="chart-card" id="card-nivel">
            <div class="chart-header">
                <h3>Nivel</h3>
                <button class="btn-mini" onclick="printSpecific('card-nivel')"><i class="fas fa-print"></i> Imprimir</button>
            </div>
            <div class="chart-container-div">
                <canvas id="chartNivel"></canvas>
            </div>
            <div class="legend-box">
                <?php foreach($datos_nivel as $d): ?>
                <div class="legend-item"><span class="color-dot" style="background:#667eea"></span> <span class="legend-text"><?php echo $d['label']; ?></span> <strong><?php echo $d['valor']; ?></strong></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="chart-card" id="card-edad">
            <div class="chart-header">
                <h3>Edad</h3>
                <button class="btn-mini" onclick="printSpecific('card-edad')"><i class="fas fa-print"></i> Imprimir</button>
            </div>
            <div class="chart-container-div">
                <canvas id="chartEdad"></canvas>
            </div>
            <div class="legend-box">
                <?php foreach($datos_edad as $d): ?>
                <div class="legend-item"><span class="color-dot" style="background:#ff7675"></span> <span class="legend-text"><?php echo $d['label']; ?></span> <strong><?php echo $d['valor']; ?></strong></div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <div class="nav-bar">
        <a href="panel_alumnos.php" class="btn btn-home">Volver</a>
        <a href="modulo_reportes.php" class="btn btn-reports">Reportes</a>
        <button onclick="window.print()" class="btn btn-print-all">IMPRIMIR TODO</button>
    </div>
</div>

<script>
    Chart.defaults.font.family = "'Segoe UI', sans-serif";
    Chart.defaults.color = '#666';
    Chart.defaults.maintainAspectRatio = false; 

    // 1. SEXO
    new Chart(document.getElementById('chartSexo'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($datos_sexo, 'label')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($datos_sexo, 'valor')); ?>,
                backgroundColor: <?php echo json_encode(array_column($datos_sexo, 'color')); ?>,
                borderWidth: 1
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });

    // 2. TURNO
    new Chart(document.getElementById('chartTurno'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_column($datos_turno, 'label')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($datos_turno, 'valor')); ?>,
                backgroundColor: <?php echo json_encode(array_column($datos_turno, 'color')); ?>,
                borderWidth: 1
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });

    // 3. NIVEL
    new Chart(document.getElementById('chartNivel'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($datos_nivel, 'label')); ?>,
            datasets: [{
                label: 'Alumnos',
                data: <?php echo json_encode(array_column($datos_nivel, 'valor')); ?>,
                backgroundColor: '#667eea', borderRadius: 4
            }]
        },
        options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }, plugins: { legend: { display: false } } }
    });

    // 4. EDAD
    new Chart(document.getElementById('chartEdad'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($datos_edad, 'label')); ?>,
            datasets: [{
                label: 'Alumnos',
                data: <?php echo json_encode(array_column($datos_edad, 'valor')); ?>,
                backgroundColor: '#ff7675', borderRadius: 4
            }]
        },
        options: { indexAxis: 'y', scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }, plugins: { legend: { display: false } } }
    });

    function printSpecific(id) {
        var element = document.getElementById(id);
        
        // 1. Añadir clases especiales
        document.body.classList.add('printing-single');
        element.classList.add('print-target');
        
        // 2. Forzar a Chart.js a redibujar el gráfico al nuevo tamaño (100% de la hoja)
        window.dispatchEvent(new Event('resize'));
        
        // 3. Esperar un poco para que el gráfico se renderice bien grande
        setTimeout(function(){
            window.print();
            
            // 4. Limpiar al terminar
            document.body.classList.remove('printing-single');
            element.classList.remove('print-target');
            
            // 5. Volver a redibujar al tamaño original
            window.dispatchEvent(new Event('resize'));
        }, 500);
    }
</script>

</body>
</html>