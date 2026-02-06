<?php
require('fpdf/fpdf.php'); 
include 'includes/conexion.php';

// Asegurar que la BD nos de los datos en UTF-8 para luego decodificarlos
mysqli_set_charset($conexion, "utf8");

// 1. OBTENER ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. CONSULTA SQL
$sql = "SELECT a.*, 
        r.nombre as nombre_rep, r.cedula as ced_rep, r.telefono,
        s.letra as letra_seccion
        FROM alumnos a 
        LEFT JOIN representantes r ON a.representante_id = r.id 
        LEFT JOIN secciones s ON a.seccion_id = s.id 
        WHERE a.id = $id";

$res = mysqli_query($conexion, $sql);
$reg = mysqli_fetch_assoc($res);

if(!$reg){ die("Error: Estudiante no encontrado."); }

// --- FUNCIÓN LOGO REDONDO (Versión compatible) ---
function crearLogoRedondo($ruta_origen) {
    if (!function_exists('imagecreatetruecolor')) return $ruta_origen;
    if (!file_exists($ruta_origen)) return false;

    $info = getimagesize($ruta_origen);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg': $src = imagecreatefromjpeg($ruta_origen); break;
        case 'image/png':  $src = imagecreatefrompng($ruta_origen); break;
        case 'image/gif':  $src = imagecreatefromgif($ruta_origen); break;
        default: return $ruta_origen;
    }

    $w = imagesx($src);
    $h = imagesy($src);
    $min = min($w, $h);
    $tamano_final = 200; 
    $dst = imagecreatetruecolor($tamano_final, $tamano_final);

    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparente = imagecolorallocatealpha($dst, 255, 255, 255, 127);
    imagefilledrectangle($dst, 0, 0, $tamano_final, $tamano_final, $transparente);

    imagecopyresampled($dst, $src, 0, 0, ($w-$min)/2, ($h-$min)/2, $tamano_final, $tamano_final, $min, $min);

    $r = $tamano_final / 2;
    for ($x = 0; $x < $tamano_final; $x++) {
        for ($y = 0; $y < $tamano_final; $y++) {
            $dx = $x - $r; $dy = $y - $r;
            if (($dx * $dx + $dy * $dy) > ($r * $r)) {
                imagesetpixel($dst, $x, $y, $transparente);
            }
        }
    }

    $ruta_destino = 'uploads/temp_logo_' . uniqid() . '.png';
    if (!file_exists('uploads')) mkdir('uploads', 0777, true);
    imagepng($dst, $ruta_destino);
    imagedestroy($src);
    imagedestroy($dst);
    
    return $ruta_destino;
}

$logo_temporal = null;

class PDF extends FPDF {
    function Header() {
        global $logo_temporal;

        // LOGO
        $ruta_logo = 'img/logo.png';
        if(file_exists($ruta_logo)) {
            $logo_temporal = crearLogoRedondo($ruta_logo);
            $img_final = ($logo_temporal && file_exists($logo_temporal)) ? $logo_temporal : $ruta_logo;
            $this->Image($img_final, 10, 8, 25); 
        }
        
        // ENCUESTA HEADER (Aquí aplicamos utf8_decode a todo lo manual)
        $this->SetFont('Arial','B',14);
        $this->Cell(0, 10, utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->SetFont('Arial','B',12);
        $this->Cell(0, 8, utf8_decode('CONSTANCIA DE INSCRIPCIÓN'), 0, 1, 'C');
        $this->SetFont('Arial','',10);
        
        $periodo = isset($GLOBALS['reg']['ano_escolar']) ? $GLOBALS['reg']['ano_escolar'] : date('Y').'-'.(date('Y')+1);
        $this->Cell(0, 5, utf8_decode('AÑO ESCOLAR: ' . $periodo), 0, 1, 'C');
        
        $this->Ln(15); 
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('Sistema de Gestión Escolar - Documento generado el '.date('d/m/Y')),0,0,'C');
    }
}

$pdf = new PDF(); 
$pdf->AliasNbPages();
$pdf->AddPage(); 
$pdf->SetFont('Arial','',11);

// --- FOTO ---
$foto_default = '690fda7a66610_img-4.jpeg'; 
$nombre_foto = $reg['foto'];
$ruta_final = 'uploads/' . $foto_default; 

if (!empty($nombre_foto) && $nombre_foto != "FALTA FOTO DEL ESTUDIANTE") {
    if (file_exists('uploads/' . $nombre_foto)) {
        $ruta_final = 'uploads/' . $nombre_foto;
    } elseif (file_exists($nombre_foto)) { 
        $ruta_final = $nombre_foto;
    }
}

if(file_exists($ruta_final)) {
    $pdf->Image($ruta_final, 165, 10, 30, 30);
    $pdf->Rect(165, 10, 30, 30);
}

// --- DATOS DEL ESTUDIANTE ---
$pdf->Ln(5);
$pdf->SetFillColor(230, 230, 250); 
$pdf->SetFont('Arial','B',11);
$pdf->Cell(190, 8, utf8_decode('  DATOS DEL ESTUDIANTE'), 1, 1, 'L', 1);

$pdf->SetFont('Arial','',10);

$pdf->Cell(35, 8, utf8_decode('Nombre:'), 1, 0, 'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(155, 8, utf8_decode($reg['nombre']), 1, 1);
$pdf->SetFont('Arial','',10);

$pdf->Cell(35, 8, utf8_decode('Cédula Escolar:'), 1, 0, 'L');
$pdf->Cell(60, 8, utf8_decode($reg['cedula_escolar']), 1, 0);
$pdf->Cell(35, 8, utf8_decode('Nivel / Grado:'), 1, 0, 'L');

$texto_nivel = $reg['nivel'];
if (!empty($reg['letra_seccion'])) {
    $texto_nivel .= ' - SECCIÓN "' . $reg['letra_seccion'] . '"';
}
$pdf->Cell(60, 8, utf8_decode($texto_nivel), 1, 1);

$pdf->Cell(35, 8, utf8_decode('Edad / Sexo:'), 1, 0, 'L');
$pdf->Cell(60, 8, utf8_decode($reg['edad'].' años  /  '.$reg['sexo']), 1, 0);
$pdf->Cell(35, 8, utf8_decode('Turno:'), 1, 0, 'L');
$pdf->Cell(60, 8, utf8_decode($reg['turno']), 1, 1);

$pdf->Cell(35, 8, utf8_decode('F. Nacimiento:'), 1, 0, 'L');
$pdf->Cell(60, 8, date("d/m/Y", strtotime($reg['fecha_nacimiento'])), 1, 0);
$pdf->Cell(35, 8, utf8_decode('Procedencia:'), 1, 0, 'L');
$pdf->Cell(60, 8, utf8_decode($reg['lugar_procedencia']), 1, 1);

// --- TALLAS Y SALUD (CORRECCIÓN APLICADA AQUÍ) ---
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
// AQUÍ ESTABA EL ERROR, AHORA ESTÁ CON UTF8_DECODE
$pdf->Cell(190, 8, utf8_decode('  DETALLES MÉDICOS Y TALLAS'), 1, 1, 'L', 1);
$pdf->SetFont('Arial','',10);

$pdf->Cell(35, 8, utf8_decode('Tallas:'), 1, 0, 'L');
$pdf->Cell(155, 8, utf8_decode("Camisa: {$reg['talla_camisa']}   |   Pantalón: {$reg['talla_pantalon']}   |   Zapatos: {$reg['talla_zapatos']}"), 1, 1);

$pdf->Cell(35, 8, utf8_decode('Condición Médica:'), 1, 0, 'L');

// Limpieza de texto de salud
$texto_salud = $reg['condicion_medica'];
if (empty($texto_salud)) { $texto_salud = 'Ninguna'; }
$texto_salud = trim($texto_salud, " |"); 
if ($texto_salud == "") { $texto_salud = 'Ninguna'; }

$x = $pdf->GetX(); $y = $pdf->GetY();
$pdf->MultiCell(155, 8, utf8_decode($texto_salud), 1, 'L');
if($pdf->GetY() - $y < 8) $pdf->Ln(); 

// --- DATOS DEL REPRESENTANTE ---
$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(190, 8, utf8_decode('  DATOS DEL REPRESENTANTE LEGAL'), 1, 1, 'L', 1);
$pdf->SetFont('Arial','',10);

$pdf->Cell(35, 8, utf8_decode('Nombre:'), 1, 0, 'L');
$pdf->Cell(155, 8, utf8_decode($reg['nombre_rep']), 1, 1);

$pdf->Cell(35, 8, utf8_decode('Cédula:'), 1, 0, 'L');
$pdf->Cell(60, 8, utf8_decode($reg['ced_rep']), 1, 0);
$pdf->Cell(35, 8, utf8_decode('Teléfono:'), 1, 0, 'L');
$pdf->Cell(60, 8, utf8_decode($reg['telefono']), 1, 1);

// --- FIRMAS ---
$pdf->Ln(35); 
$pdf->SetFont('Arial','',10);
$y_firmas = $pdf->GetY();

$pdf->Line(20, $y_firmas, 80, $y_firmas);
$pdf->Text(25, $y_firmas + 5, utf8_decode("Firma del Representante"));

$pdf->Line(130, $y_firmas, 190, $y_firmas);
$pdf->Text(135, $y_firmas + 5, utf8_decode("Sello y Firma Institucional"));

$pdf->Output('I', "Inscripcion_".str_replace(" ", "_", $reg['nombre']).".pdf");

if($logo_temporal && file_exists($logo_temporal)) {
    unlink($logo_temporal);
}
?>