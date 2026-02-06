<?php
include 'includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* ==========================================
       1. PROCESAR REPRESENTANTE (14 CAMPOS)
    ========================================== */
    $representante_id = null;
    $cedula_rep = mysqli_real_escape_string($conexion, $_POST['cedula_representante']);

    $buscar_rep = mysqli_query($conexion, "SELECT id FROM representantes WHERE cedula = '$cedula_rep'");

    if(mysqli_num_rows($buscar_rep) > 0) {
        $rep_data = mysqli_fetch_assoc($buscar_rep);
        $representante_id = $rep_data['id'];
        
        // OPCIONAL: Actualizar datos del representante existente si cambiaron
        // $sql_update_rep = "UPDATE representantes SET ... WHERE id=$representante_id";
        // mysqli_query($conexion, $sql_update_rep);
    } else {
        $nom_r  = mysqli_real_escape_string($conexion, strtoupper($_POST['nombre_representante']));
        $tel_r  = mysqli_real_escape_string($conexion, $_POST['telefono_representante']);
        $cor_r  = mysqli_real_escape_string($conexion, $_POST['correo_representante']);
        $con_r  = mysqli_real_escape_string($conexion, strtoupper($_POST['condicion_representante']));
        $dir_h  = mysqli_real_escape_string($conexion, strtoupper($_POST['direccion_representante']));
        $dir_t  = mysqli_real_escape_string($conexion, strtoupper($_POST['direccion_trabajo_representante']));
        $prof_r = mysqli_real_escape_string($conexion, strtoupper($_POST['profesion_representante']));
        $edad_r = intval($_POST['edad_representante']);
        $nac_r  = mysqli_real_escape_string($conexion, strtoupper($_POST['nacionalidad_representante']));
        $inst_r = mysqli_real_escape_string($conexion, $_POST['nivel_instruccion_representante']);
        $ocu_r  = mysqli_real_escape_string($conexion, strtoupper($_POST['ocupacion_representante']));

        $sql_rep = "INSERT INTO representantes (
            nombre, cedula, telefono, correo, condicion, 
            direccion_representante, direccion_trabajo_representante, 
            profesion_representante, edad_representante, 
            nacionalidad_representante, nivel_instruccion_representante, 
            ocupacion_representante
        ) VALUES (
            '$nom_r', '$cedula_rep', '$tel_r', '$cor_r', '$con_r', 
            '$dir_h', '$dir_t', '$prof_r', $edad_r, 
            '$nac_r', '$inst_r', '$ocu_r'
        )";

        if(!mysqli_query($conexion, $sql_rep)) {
            die("Error guardando representante: " . mysqli_error($conexion));
        }
        $representante_id = mysqli_insert_id($conexion);
    }

    /* ==========================================
       2. PROCESAR SALUD Y CONDICIONES
    ========================================== */
    $condiciones = isset($_POST['condicion_medica']) ? $_POST['condicion_medica'] : [];
    $detalle_salud = implode(", ", $condiciones);
    if(!empty($_POST['detalle_alergia'])) $detalle_salud .= " | Alergia: ".$_POST['detalle_alergia'];
    if(!empty($_POST['detalle_discapacidad'])) $detalle_salud .= " | Especial: ".$_POST['detalle_discapacidad'];
    if(!empty($_POST['detalle_otros'])) $detalle_salud .= " | Otros: ".$_POST['detalle_otros'];
    
    $detalle_salud = mysqli_real_escape_string($conexion, strtoupper($detalle_salud));

    /* ==========================================
       3. GESTIÓN DE FOTO
    ========================================== */
    $foto_nombre = "";
    $carpeta_fotos = "uploads/";
    // Asegurar que la carpeta existe
    if (!file_exists($carpeta_fotos)) { mkdir($carpeta_fotos, 0777, true); }
    
    $foto_default = "690fda7a66610_img-4.jpeg"; 

    if(!empty($_FILES['foto_alumno']['name'])){
        $extension = pathinfo($_FILES['foto_alumno']['name'], PATHINFO_EXTENSION);
        $foto_nombre = time() . "_" . bin2hex(random_bytes(5)) . "." . $extension;
        if(!move_uploaded_file($_FILES['foto_alumno']['tmp_name'], $carpeta_fotos . $foto_nombre)){
            $foto_nombre = $foto_default; // Si falla la subida, usar default
        }
    } else {
        $foto_nombre = $foto_default;
    }

    /* ==========================================
       4. INSERTAR ALUMNO
    ========================================== */
    $docs = [
        'pn' => isset($_POST['doc_partida_nacimiento']) ? 1 : 0,
        'cm' => isset($_POST['doc_copia_cedula_madre']) ? 1 : 0,
        'cp' => isset($_POST['doc_copia_cedula_padre']) ? 1 : 0,
        'fc' => isset($_POST['doc_fotos_carnet']) ? 1 : 0,
        'cv' => isset($_POST['doc_certificado_vacunas']) ? 1 : 0
    ];

    $nom_a      = mysqli_real_escape_string($conexion, strtoupper($_POST['nombre_alumno']));
    $ced_e      = mysqli_real_escape_string($conexion, $_POST['cedula_escolar']);
    $f_nac      = $_POST['fecha_nacimiento'];
    $edad_a     = intval($_POST['edad']);
    $sexo_a     = $_POST['sexo'];
    $niv_a      = $_POST['nivel'];
    $tur_a      = $_POST['turno'];
    
    // --- DATOS ACADÉMICOS ---
    $aul_a      = $_POST['aula_id']; 
    $seccion_id = !empty($_POST['seccion_id']) ? intval($_POST['seccion_id']) : "NULL";
    $docente_id = intval($_POST['docente_id']);
    
    // --- DATOS PERSONALES ---
    $proce      = $_POST['lugar_procedencia'];
    $det_pro    = mysqli_real_escape_string($conexion, strtoupper($_POST['detalle_procedencia']));
    $dir_a      = mysqli_real_escape_string($conexion, strtoupper($_POST['direccion_nino']));
    $nac_a      = mysqli_real_escape_string($conexion, strtoupper($_POST['nacionalidad']));
    $mun_a      = mysqli_real_escape_string($conexion, strtoupper($_POST['municipio']));
    $par_a      = mysqli_real_escape_string($conexion, strtoupper($_POST['parroquia']));
    $t_cam      = $_POST['talla_camisa'];
    $t_pan      = $_POST['talla_pantalon'];
    $t_zap      = $_POST['talla_zapatos'];
    $periodo    = $_POST['ano_escolar'];

    // --- NUEVO: CAPTURAR LA FECHA MANUAL ---
    $fecha_reg  = $_POST['fecha_registro_manual']; 

    // --- SQL INSERT ACTUALIZADO (INCLUYE fecha_registro) ---
    $sql_alumno = "INSERT INTO alumnos (
        nombre, cedula_escolar, fecha_nacimiento, edad, sexo, nivel, 
        condicion_medica, foto, representante_id, docente_id, lugar_procedencia, 
        detalle_procedencia, direccion_nino, nacionalidad, municipio, 
        parroquia, estado, talla_camisa, talla_pantalon, talla_zapatos, 
        ano_escolar, doc_partida_nacimiento, doc_copia_cedula_madre, 
        doc_copia_cedula_padre, doc_fotos_carnet, doc_certificado_vacunas, 
        aula, turno, seccion_id, fecha_registro
    ) VALUES (
        '$nom_a', '$ced_e', '$f_nac', $edad_a, '$sexo_a', '$niv_a', 
        '$detalle_salud', '$foto_nombre', $representante_id, $docente_id, '$proce', 
        '$det_pro', '$dir_a', '$nac_a', '$mun_a', 
        '$par_a', 'MONAGAS', '$t_cam', '$t_pan', '$t_zap', 
        '$periodo', {$docs['pn']}, {$docs['cm']}, 
        {$docs['cp']}, {$docs['fc']}, {$docs['cv']}, 
        '$aul_a', '$tur_a', $seccion_id, '$fecha_reg'
    )";

    if(mysqli_query($conexion, $sql_alumno)) {
        $alumno_id = mysqli_insert_id($conexion);
        
        echo "<!DOCTYPE html><html><head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <style>body{font-family:sans-serif;background:#f4f4f4;}</style>
        </head><body>
        <script>
            Swal.fire({
                title: '¡Inscripción Exitosa!',
                text: 'El alumno ha sido registrado correctamente con fecha: $fecha_reg',
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Ver Ficha PDF',
                cancelButtonText: 'Volver al Panel',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#667eea'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('comprobante_pdf.php?id=$alumno_id', '_blank');
                    window.location.href = 'panel_alumnos.php';
                } else {
                    window.location.href = 'panel_alumnos.php';
                }
            });
        </script></body></html>";
    } else {
        echo "<h3 style='color:red'>Error Crítico:</h3> " . mysqli_error($conexion);
    }
}
?>