<?php
session_start();
include 'includes/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    exit("Acceso Denegado");
}

// 1. DETECTAR SI SE QUIERE EXPORTAR
$exportar = isset($_GET['exportar']) ? $_GET['exportar'] : null;
$nombre_archivo = "Historial_Academico_" . date('d-m-Y');

if ($exportar == 'excel') {
    // Forzar descarga como Excel (.xls)
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=$nombre_archivo.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
} elseif ($exportar == 'word') {
    // Forzar descarga como Word (.doc)
    header("Content-Type: application/vnd.ms-word; charset=utf-8");
    header("Content-Disposition: attachment; filename=$nombre_archivo.doc");
    header("Pragma: no-cache");
    header("Expires: 0");
}

// 2. CONSULTA DE DATOS
$periodo_actual = "2025-2026"; 
$sql = "SELECT * FROM alumnos ORDER BY nivel DESC, nombre ASC";
$query = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Historial Académico</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 40px; background: white; }
        
        /* Estilos generales de tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: middle; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        
        .header { text-align: center; margin-bottom: 30px; }
        .titulo-doc { font-weight: bold; font-size: 14px; text-decoration: underline; margin-top: 10px; display: block; }
        
        /* Ocultar elementos al imprimir o exportar */
        <?php if($exportar): ?>
            .no-export { display: none; }
        <?php else: ?>
            .botones-accion { 
                position: fixed; top: 0; left: 0; width: 100%; 
                background: #333; padding: 10px; text-align: center; 
                box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 999; 
            }
            body { margin-top: 70px; } /* Espacio para la barra fija */
            
            .btn { 
                display: inline-block; padding: 8px 15px; margin: 0 5px; 
                color: white; text-decoration: none; border-radius: 4px; 
                font-family: sans-serif; font-weight: bold; cursor: pointer; border: none;
            }
            .btn-print { background: #95a5a6; }
            .btn-excel { background: #27ae60; }
            .btn-word { background: #2980b9; }
            .btn:hover { opacity: 0.9; }
            
            @media print { .botones-accion { display: none; } body { margin-top: 0; } }
        <?php endif; ?>
        
        .text-center { text-align: center; }
        .resumen { margin-top: 30px; border: 1px solid #000; padding: 10px; }
        .firmas { margin-top: 60px; width: 100%; }
        .col-firma { width: 45%; display: inline-block; text-align: center; vertical-align: top; }
        .linea { border-top: 1px solid #000; width: 80%; margin: 0 auto 5px auto; }
    </style>
</head>
<body>

    <?php if(!$exportar): ?>
    <div class="botones-accion no-export">
        <button onclick="window.print()" class="btn btn-print">🖨️ Imprimir</button>
        <a href="reporte_sabana.php?exportar=excel" class="btn btn-excel">📊 Descargar Excel</a>
        <a href="reporte_sabana.php?exportar=word" class="btn btn-word">📝 Descargar Word</a>
    </div>
    <?php endif; ?>

    <div class="header">
        <?php if(!$exportar): ?>
            <img src="img/logo.png" style="width: 60px; height: 60px;"><br>
        <?php else: ?>
            <h3>REPÚBLICA BOLIVARIANA DE VENEZUELA</h3>
        <?php endif; ?>
        
        MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN<br>
        COLEGIO "JOSÉ AGUSTÍN MÉNDEZ GARCÍA"<br>
        MATURÍN - ESTADO MONAGAS<br>
        <br>
        <span class="titulo-doc">RESUMEN DE RENDIMIENTO ESTUDIANTIL - PERIODO <?php echo $periodo_actual; ?></span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30" style="background:#e0e0e0;">N°</th>
                <th style="background:#e0e0e0;">CÉDULA ESCOLAR</th>
                <th style="background:#e0e0e0;">APELLIDOS Y NOMBRES</th>
                <th style="background:#e0e0e0;">EDAD</th>
                <th style="background:#e0e0e0;">NIVEL</th>
                <th style="background:#e0e0e0;">ESTADO FINAL</th>
                <th style="background:#e0e0e0;">OBSERVACIÓN</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            $cont_promovidos = 0; $cont_reprobados = 0; $cont_retirados = 0;

            if(mysqli_num_rows($query) > 0):
                while($row = mysqli_fetch_assoc($query)): 
                    // Contadores
                    if($row['estado_promocion'] == 'Promovido') $cont_promovidos++;
                    elseif($row['estado_promocion'] == 'Reprobado') $cont_reprobados++;
                    elseif($row['estado_promocion'] == 'Retirado') $cont_retirados++;
                    
                    // Colores visuales
                    $color = '#000';
                    if($row['estado_promocion'] == 'Promovido') $color = 'green';
                    if($row['estado_promocion'] == 'Retirado') $color = 'red';
                    if($row['estado_promocion'] == 'Reprobado') $color = '#d35400';
            ?>
            <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td><?php echo $row['cedula_escolar']; ?></td>
                <td><?php echo utf8_encode($row['nombre']); // utf8_encode por si acaso caracteres raros ?></td>
                <td class="text-center"><?php echo $row['edad']; ?></td>
                <td class="text-center"><?php echo $row['nivel']; ?></td>
                <td class="text-center" style="color:<?php echo $color; ?>; font-weight:bold;">
                    <?php echo strtoupper($row['estado_promocion']); ?>
                </td>
                <td></td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No hay alumnos registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>

    <table style="width: 60%; margin: 0 auto; border: 1px solid #000;">
        <tr>
            <th colspan="2" style="background:#ddd; text-align:center;">ESTADÍSTICA FINAL</th>
        </tr>
        <tr>
            <td><strong>Matrícula Total:</strong></td>
            <td class="text-center"><?php echo $i - 1; ?></td>
        </tr>
        <tr>
            <td><strong>Promovidos:</strong></td>
            <td class="text-center"><?php echo $cont_promovidos; ?></td>
        </tr>
        <tr>
            <td><strong>Reprobados:</strong></td>
            <td class="text-center"><?php echo $cont_reprobados; ?></td>
        </tr>
        <tr>
            <td><strong>Retirados:</strong></td>
            <td class="text-center"><?php echo $cont_retirados; ?></td>
        </tr>
    </table>

    <br><br><br>

    <table style="border:none;">
        <tr>
            <td style="border:none; text-align:center; width:50%;">
                <div style="border-top:1px solid #000; width:60%; margin:0 auto;"></div>
                <strong>Director(a)</strong><br>
                Firma y Sello
            </td>
            <td style="border:none; text-align:center; width:50%;">
                <div style="border-top:1px solid #000; width:60%; margin:0 auto;"></div>
                <strong>Coord. Evaluación</strong><br>
                Firma
            </td>
        </tr>
    </table>

</body>
</html>