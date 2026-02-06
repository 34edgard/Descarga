<?php
require('fpdf/fpdf.php');
include 'includes/conexion.php';

// Reconstruir la consulta basada en los parámetros GET
$where = "WHERE 1=1";
$titulo_reporte = "REPORTE GENERAL DE MATRÍCULA";

if (!empty($_GET['nivel'])) {
    $n = mysqli_real_escape_string($conexion, $_GET['nivel']);
    $where .= " AND nivel = '$n'";
    $titulo_reporte = "MATRÍCULA - $n";
}
if (!empty($_GET['turno'])) {
    $t = mysqli_real_escape_string($conexion, $_GET['turno']);
    $where .= " AND turno = '$t'";
    $titulo_reporte .= " (TURNO $t)"; // Concatena al título
}

// Ejecutar consulta
$sql = "SELECT * FROM alumnos $where ORDER BY nivel, nombre ASC";
$res = mysqli_query($conexion, $sql);

// Calcular totales para el pie de página
$total = mysqli_num_rows($res);
$m = 0; $f = 0;

class PDF extends FPDF {
    var $titulo; // Variable para pasar el título dinámico

    function Header() {
        if(file_exists('img/logo.png')) $this->Image('img/logo.png', 10, 8, 25);
        $this->SetFont('Arial','B',16);
        $this->Cell(0, 10, utf8_decode('SISTEMA DE CONTROL DE ESTUDIANTES'), 0, 1, 'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(0, 10, utf8_decode($this->titulo), 0, 1, 'C');
        $this->Ln(5);

        // Encabezado de Tabla
        $this->SetFillColor(102, 126, 234); // Color morado/azul del sistema
        $this->SetTextColor(255);
        $this->SetFont('Arial','B',10);
        
        // Anchos de columnas (Total 277mm aprox en horizontal)
        $this->Cell(10, 8, '#', 1, 0, 'C', 1);
        $this->Cell(35, 8, utf8_decode('CÉDULA'), 1, 0, 'C', 1);
        $this->Cell(90, 8, 'NOMBRE Y APELLIDO', 1, 0, 'C', 1);
        $this->Cell(15, 8, 'EDAD', 1, 0, 'C', 1);
        $this->Cell(25, 8, 'SEXO', 1, 0, 'C', 1);
        $this->Cell(30, 8, 'NIVEL', 1, 0, 'C', 1);
        $this->Cell(30, 8, 'TURNO', 1, 0, 'C', 1);
        $this->Cell(40, 8, 'PROCEDENCIA', 1, 1, 'C', 1);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetTextColor(0);
        $this->SetFont('Arial','I',8);
        $this->Cell(0, 10, utf8_decode('Fecha de impresión: ' . date('d/m/Y') . ' - Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Crear PDF Horizontal (L = Landscape)
$pdf = new PDF('L', 'mm', 'A4');
$pdf->titulo = $titulo_reporte; // Pasar el título dinámico
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0);

$i = 1;
while($row = mysqli_fetch_assoc($res)) {
    // Contar sexos
    if($row['sexo'] == 'M' || $row['sexo'] == 'Masculino') $m++; else $f++;

    $pdf->Cell(10, 8, $i++, 1, 0, 'C');
    $pdf->Cell(35, 8, $row['cedula_escolar'], 1, 0, 'C');
    $pdf->Cell(90, 8, utf8_decode(substr($row['nombre'], 0, 45)), 1, 0, 'L'); // Cortar nombre si es muy largo
    $pdf->Cell(15, 8, $row['edad'], 1, 0, 'C');
    $pdf->Cell(25, 8, ($row['sexo']=='M' || $row['sexo']=='Masculino') ? 'MASC' : 'FEM', 1, 0, 'C');
    $pdf->Cell(30, 8, utf8_decode($row['nivel']), 1, 0, 'C');
    $pdf->Cell(30, 8, utf8_decode($row['turno']), 1, 0, 'C');
    $pdf->Cell(40, 8, utf8_decode($row['lugar_procedencia']), 1, 1, 'C');
}

// Resumen estadístico al final
$pdf->Ln(10);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0, 10, 'RESUMEN ESTADISTICO', 0, 1, 'L');
$pdf->SetFont('Arial','',11);
$pdf->Cell(50, 8, utf8_decode('Total Estudiantes: ' . $total), 0, 1);
$pdf->Cell(50, 8, utf8_decode('Varones: ' . $m), 0, 1);
$pdf->Cell(50, 8, utf8_decode('Hembras: ' . $f), 0, 1);

$pdf->Output('I', 'Reporte_Matricula.pdf');
?>