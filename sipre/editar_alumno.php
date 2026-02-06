<?php
session_start();
include 'includes/conexion.php';

// Validar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$alumno_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($alumno_id <= 0) {
    header("Location: panel_alumnos.php");
    exit;
}

// 1. OBTENER DATOS ACTUALES
$sql_full = "SELECT a.*, 
             r.nombre as r_nombre, r.cedula as r_cedula, r.telefono as r_telefono, 
             r.correo as r_correo, r.condicion as r_condicion, 
             r.profesion_representante as r_profesion, r.ocupacion_representante as r_ocupacion,
             r.edad_representante as r_edad, r.nacionalidad_representante as r_nacionalidad,
             r.nivel_instruccion_representante as r_instruccion, 
             r.direccion_representante as r_direccion, r.direccion_trabajo_representante as r_trabajo
             FROM alumnos a 
             LEFT JOIN representantes r ON a.representante_id = r.id 
             WHERE a.id = $alumno_id";

$res = mysqli_query($conexion, $sql_full);
if (!$res || mysqli_num_rows($res) == 0) {
    header("Location: panel_alumnos.php");
    exit;
}
$datos = mysqli_fetch_assoc($res);

// Lógica de foto
$foto_default = '690fda7a66610_img-4.jpeg';
$foto_db = $datos['foto'];
$ruta_mostrar = (empty($foto_db) || $foto_db == "FALTA FOTO DEL ESTUDIANTE") ? "uploads/$foto_default" : "uploads/$foto_db";
if(!file_exists($ruta_mostrar)) { 
    if(file_exists($foto_db)) { $ruta_mostrar = $foto_db; } 
    else { $ruta_mostrar = "uploads/$foto_default"; }
}

// 2. PROCESAR GUARDADO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Alumno
    $nombre = mysqli_real_escape_string($conexion, strtoupper($_POST['nombre_alumno']));
    $cedula_esc = mysqli_real_escape_string($conexion, $_POST['cedula_escolar']);
    $f_nac = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $nivel = $_POST['nivel'];
    $turno = $_POST['turno'];
    
    // --- CORRECCIÓN DE AULA Y SECCIÓN ---
    $aula_id = !empty($_POST['aula_id']) ? "'" . mysqli_real_escape_string($conexion, $_POST['aula_id']) . "'" : "NULL";
    $seccion_id = !empty($_POST['seccion_id']) ? intval($_POST['seccion_id']) : "NULL";

    $procedencia = $_POST['lugar_procedencia'];
    $det_procedencia = mysqli_real_escape_string($conexion, strtoupper($_POST['detalle_procedencia']));
    $direccion = mysqli_real_escape_string($conexion, strtoupper($_POST['direccion_nino']));
    $municipio = mysqli_real_escape_string($conexion, strtoupper($_POST['municipio']));
    $parroquia = mysqli_real_escape_string($conexion, strtoupper($_POST['parroquia']));
    $t_camisa = $_POST['talla_camisa'];
    $t_pantalon = $_POST['talla_pantalon'];
    $t_zapatos = $_POST['talla_zapatos'];
    $periodo = $_POST['ano_escolar'];
    $docente_id = intval($_POST['docente_id']);
    
    // NUEVO: FECHA DE REGISTRO MANUAL
    $fecha_reg = $_POST['fecha_registro_manual'];

    // Salud
    $cond_array = isset($_POST['condicion_medica']) ? $_POST['condicion_medica'] : [];
    $salud_final = implode(", ", $cond_array);
    if(!empty($_POST['detalle_alergia'])) $salud_final .= " | ALERGIA: ".$_POST['detalle_alergia'];
    if(!empty($_POST['detalle_discapacidad'])) $salud_final .= " | ESPECIAL: ".$_POST['detalle_discapacidad'];
    if(!empty($_POST['detalle_otros'])) $salud_final .= " | OTROS: ".$_POST['detalle_otros'];
    $salud_final = mysqli_real_escape_string($conexion, strtoupper($salud_final));

    // Documentos
    $pn = isset($_POST['doc_partida_nacimiento']) ? 1 : 0;
    $cm = isset($_POST['doc_copia_cedula_madre']) ? 1 : 0;
    $cp = isset($_POST['doc_copia_cedula_padre']) ? 1 : 0;
    $fc = isset($_POST['doc_fotos_carnet']) ? 1 : 0;
    $cv = isset($_POST['doc_certificado_vacunas']) ? 1 : 0;

    // Calcular edad
    $f_n_obj = new DateTime($f_nac);
    $hoy = new DateTime();
    $edad = $hoy->diff($f_n_obj)->y;

    // Foto nueva
    $sql_foto = "";
    if (!empty($_FILES['nueva_foto']['name'])) {
        $ext = pathinfo($_FILES['nueva_foto']['name'], PATHINFO_EXTENSION);
        $nom_f = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
        if (move_uploaded_file($_FILES['nueva_foto']['tmp_name'], "uploads/" . $nom_f)) {
            $sql_foto = ", foto='$nom_f'";
        }
    }

    // Representante
    $r_nombre = mysqli_real_escape_string($conexion, strtoupper($_POST['nombre_representante']));
    $r_cedula = mysqli_real_escape_string($conexion, $_POST['cedula_representante']);
    $r_telefono = mysqli_real_escape_string($conexion, $_POST['telefono_representante']);
    $r_correo = mysqli_real_escape_string($conexion, $_POST['correo_representante']);
    $r_condicion = mysqli_real_escape_string($conexion, strtoupper($_POST['condicion_representante']));
    $r_profesion = mysqli_real_escape_string($conexion, strtoupper($_POST['profesion_representante']));
    $r_ocupacion = mysqli_real_escape_string($conexion, strtoupper($_POST['ocupacion_representante']));
    $r_edad = intval($_POST['edad_representante']);
    $r_nacionalidad = mysqli_real_escape_string($conexion, strtoupper($_POST['nacionalidad_representante']));
    $r_instruccion = $_POST['nivel_instruccion_representante'];
    $r_direccion = mysqli_real_escape_string($conexion, strtoupper($_POST['direccion_representante']));
    $r_trabajo = mysqli_real_escape_string($conexion, strtoupper($_POST['direccion_trabajo_representante']));

    // Update Representante
    $id_rep = $datos['representante_id'];
    if($id_rep > 0) {
        $sql_rep = "UPDATE representantes SET 
                    nombre='$r_nombre', cedula='$r_cedula', telefono='$r_telefono', correo='$r_correo', 
                    condicion='$r_condicion', profesion_representante='$r_profesion', 
                    ocupacion_representante='$r_ocupacion', edad_representante=$r_edad, 
                    nacionalidad_representante='$r_nacionalidad', nivel_instruccion_representante='$r_instruccion', 
                    direccion_representante='$r_direccion', direccion_trabajo_representante='$r_trabajo'
                    WHERE id = $id_rep";
        mysqli_query($conexion, $sql_rep);
    }

    // Update Alumno
    $sql_update = "UPDATE alumnos SET 
        nombre='$nombre', cedula_escolar='$cedula_esc', fecha_nacimiento='$f_nac', 
        sexo='$sexo', edad=$edad, nivel='$nivel', turno='$turno', 
        aula=$aula_id, seccion_id=$seccion_id,  
        lugar_procedencia='$procedencia', 
        detalle_procedencia='$det_procedencia', direccion_nino='$direccion', 
        municipio='$municipio', parroquia='$parroquia', talla_camisa='$t_camisa', 
        talla_pantalon='$t_pantalon', talla_zapatos='$t_zapatos', docente_id=$docente_id,
        condicion_medica='$salud_final', ano_escolar='$periodo',
        doc_partida_nacimiento=$pn, doc_copia_cedula_madre=$cm, 
        doc_copia_cedula_padre=$cp, doc_fotos_carnet=$fc, doc_certificado_vacunas=$cv,
        fecha_registro='$fecha_reg' 
        $sql_foto 
        WHERE id = $alumno_id";

    if (mysqli_query($conexion, $sql_update)) {
        $_SESSION['mensaje_exito'] = "Expediente actualizado correctamente.";
        echo "<script>window.location.href='panel_alumnos.php';</script>";
        exit;
    } else {
        $error = "Error SQL: " . mysqli_error($conexion);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Expediente - SIPRE</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; --success: #28a745; --warning: #f39c12; --blue: #3498db; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); padding: 20px; min-height: 100vh; margin: 0; }
        .formulario { background: #fff; max-width: 950px; margin: auto; padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); }
        .seccion { background: #fdfdfd; padding: 25px; margin-bottom: 25px; border-radius: 12px; border: 1px solid #eee; border-left: 5px solid var(--primary); }
        h2 { text-align: center; color: #333; text-transform: uppercase; margin-bottom: 30px; letter-spacing: 1px; }
        h3 { margin-bottom: 20px; color: var(--secondary); display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; }
        label { font-weight: 600; display: block; margin-top: 12px; color: #444; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 12px; border-radius: 8px; border: 2px solid #e1e1e1; margin-top: 6px; box-sizing: border-box; font-size: 15px; transition: 0.3s; }
        input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 8px rgba(102,126,234,0.2); }
        .fila-doble { display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-end; }
        .fila-doble > div { flex: 1; min-width: 200px; }
        .btn-plus { background: var(--success); color: white; border: none; border-radius: 8px; cursor: pointer; height: 48px; width: 48px; font-size: 18px; transition: 0.3s; }
        .btn-plus:hover { transform: scale(1.05); background: #218838; }
        .preview-circular { width: 130px; height: 130px; border-radius: 50%; border: 4px solid var(--primary); object-fit: cover; margin: 0 auto 10px; display: block; background: #eee; }
        .boton { background: var(--primary); color: #fff; padding: 15px 30px; border-radius: 10px; border: none; cursor: pointer; font-weight: bold; display: inline-flex; align-items: center; gap: 10px; text-decoration: none; transition: 0.3s; }
        .boton:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .boton-pdf { background: var(--blue); }
        .boton-pdf:hover { background: #2980b9; }
        .checkbox-group { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; padding: 15px; background: #fff; border-radius: 8px; border: 1px solid #ddd; }
        .checkbox-item { display: flex; align-items: center; gap: 10px; font-weight: 500; cursor: pointer; }
        .condicion-extra { display: none; margin-top: 10px; }
        .modal { display: none; position: fixed; z-index: 3000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); align-items: center; justify-content: center; }
        .modal-content { background: white; padding: 30px; border-radius: 15px; width: 400px; position: relative; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div id="modalAula" class="modal">
    <div class="modal-content">
        <h3><i class="fas fa-school"></i> Nueva Aula / Grupo</h3>
        <label>Nombre del Grupo (Ej: GRUPO A):</label>
        <input type="text" id="new_nombre_aula" class="solo-mayus">
        <button type="button" class="boton" style="width:100%; margin-top:15px; background:var(--success);" onclick="guardarAula()">Guardar Aula</button>
        <button type="button" onclick="cerrarModal('modalAula')" style="width:100%; margin-top:10px; background:none; border:none; color:red; cursor:pointer;">Cancelar</button>
    </div>
</div>

<div id="modalSeccion" class="modal">
    <div class="modal-content">
        <h3><i class="fas fa-layer-group"></i> Nueva Sección</h3>
        <label>Letra de la Sección (Ej: B):</label>
        <input type="text" id="new_letra_seccion" maxlength="1" class="solo-mayus">
        <button type="button" class="boton" style="width:100%; margin-top:15px; background:var(--success);" onclick="guardarSeccion()">Guardar Sección</button>
        <button type="button" onclick="cerrarModal('modalSeccion')" style="width:100%; margin-top:10px; background:none; border:none; color:red; cursor:pointer;">Cancelar</button>
    </div>
</div>

<div class="formulario">
    <h2><i class="fas fa-edit"></i> Editar Expediente Escolar</h2>

    <?php if (isset($error)): ?>
        <div style="background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin-bottom:20px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        
        <div style="text-align:center; margin-bottom:20px;">
            <img src="<?php echo $ruta_mostrar; ?>" id="img-preview" class="preview-circular">
            <label for="nueva_foto" class="boton" style="padding: 8px 15px; font-size: 13px; cursor:pointer; width:auto;"><i class="fas fa-camera"></i> Cambiar Foto</label>
            <input type="file" name="nueva_foto" id="nueva_foto" accept="image/*" style="display:none" onchange="previewImage(event)">
        </div>

        <div class="seccion" style="border-left-color: var(--warning);">
            <h3><i class="fas fa-university"></i> 1. Ubicación Académica</h3>
            <div class="fila-doble">
                <div>
                    <label>Nivel *</label>
                    <select name="nivel" id="nivel" required onchange="cargarAulas()">
                        <option value="1°" <?php if($datos['nivel']=='1°') echo 'selected'; ?>>1° NIVEL</option>
                        <option value="2°" <?php if($datos['nivel']=='2°') echo 'selected'; ?>>2° NIVEL</option>
                        <option value="3°" <?php if($datos['nivel']=='3°') echo 'selected'; ?>>3° NIVEL</option>
                    </select>
                </div>
                <div>
                    <label>Turno *</label>
                    <select name="turno" id="turno" required onchange="cargarAulas()">
                        <option value="Mañana" <?php if($datos['turno']=='Mañana') echo 'selected'; ?>>MAÑANA</option>
                        <option value="Tarde" <?php if($datos['turno']=='Tarde') echo 'selected'; ?>>TARDE</option>
                    </select>
                </div>
            </div>
            
            <div class="fila-doble">
                <div style="flex: 8;">
                    <label>Aula / Grupo *</label>
                    <select name="aula_id" id="select_aula" required onchange="cargarSecciones()">
                        <option value="">Seleccione...</option>
                        <?php
                        // Carga inicial
                        $q_aulas = mysqli_query($conexion, "SELECT * FROM aulas WHERE nivel='{$datos['nivel']}' AND turno='{$datos['turno']}'");
                        while($a = mysqli_fetch_assoc($q_aulas)) {
                            $sel = ($datos['aula'] == $a['id']) ? 'selected' : '';
                            echo "<option value='{$a['id']}' $sel>{$a['nombre_grupo']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div style="flex: 1;"><button type="button" class="btn-plus" onclick="abrirModal('modalAula')"><i class="fas fa-plus"></i></button></div>
            </div>

            <div class="fila-doble">
                <div style="flex: 8;">
                    <label>Sección Asignada</label>
                    <select name="seccion_id" id="select_seccion">
                        <option value="">-- Sin Asignar --</option>
                        <?php
                        if(!empty($datos['aula'])) {
                            $q_sec = mysqli_query($conexion, "SELECT * FROM secciones WHERE aula_id='{$datos['aula']}' ORDER BY letra");
                            while($s = mysqli_fetch_assoc($q_sec)) {
                                $sel = ($datos['seccion_id'] == $s['id']) ? 'selected' : '';
                                echo "<option value='{$s['id']}' $sel>Sección {$s['letra']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div style="flex: 1;"><button type="button" class="btn-plus" onclick="abrirModal('modalSeccion')"><i class="fas fa-plus"></i></button></div>
            </div>

            <label>Docente Guía *</label>
            <select name="docente_id" required>
                <option value="">Seleccione Docente...</option>
                <?php 
                $res_doc = mysqli_query($conexion, "SELECT id, nombre FROM docentes");
                while($d = mysqli_fetch_assoc($res_doc)){
                    $selected = ($datos['docente_id'] == $d['id']) ? 'selected' : '';
                    echo "<option value='{$d['id']}' $selected>{$d['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="seccion">
            <h3><i class="fas fa-child"></i> 2. Información del Alumno</h3>
            <label>Nombre Completo *</label>
            <input type="text" name="nombre_alumno" class="solo-letras-mayus" value="<?php echo htmlspecialchars($datos['nombre']); ?>" required>

            <div class="fila-doble">
                <div><label>Cédula Escolar</label><input type="text" name="cedula_escolar" id="cedula_escolar" value="<?php echo $datos['cedula_escolar']; ?>" readonly style="background:#eee"></div>
                <div><label>Fecha de Nacimiento *</label><input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo $datos['fecha_nacimiento']; ?>" required onchange="calcularEdad()"></div>
            </div>

            <div class="fila-doble">
                <div><label>Edad</label><input type="text" id="edad_texto" value="<?php echo $datos['edad']; ?> AÑOS" readonly style="background:#eee"></div>
                <div>
                    <label>Sexo *</label>
                    <select name="sexo" required>
                        <option value="M" <?php if($datos['sexo']=='M') echo 'selected'; ?>>MASCULINO</option>
                        <option value="F" <?php if($datos['sexo']=='F') echo 'selected'; ?>>FEMENINO</option>
                    </select>
                </div>
            </div>

            <div class="fila-doble">
                <div>
                    <label>Lugar de Procedencia *</label>
                    <select name="lugar_procedencia" id="lugar_procedencia" required onchange="toggleDetalleProcedencia()">
                        <option value="Hogar" <?php if($datos['lugar_procedencia']=='Hogar') echo 'selected'; ?>>HOGAR</option>
                        <option value="Colegio" <?php if($datos['lugar_procedencia']=='Colegio') echo 'selected'; ?>>COLEGIO</option>
                        <option value="Casa Hogar" <?php if($datos['lugar_procedencia']=='Casa Hogar') echo 'selected'; ?>>CASA HOGAR</option>
                        <option value="Otro" <?php if($datos['lugar_procedencia']=='Otro') echo 'selected'; ?>>OTRO</option>
                    </select>
                </div>
                <div id="detalle_procedencia_container" style="display: <?php echo ($datos['lugar_procedencia']=='Otro') ? 'block' : 'none'; ?>">
                    <label>Especifique Procedencia</label>
                    <input type="text" name="detalle_procedencia" class="solo-mayus" value="<?php echo htmlspecialchars($datos['detalle_procedencia']); ?>">
                </div>
            </div>

            <label>Dirección de Residencia *</label>
            <textarea name="direccion_nino" rows="2" class="solo-mayus" required><?php echo htmlspecialchars($datos['direccion_nino']); ?></textarea>

            <div class="fila-doble">
                <div>
                    <label>Municipio</label>
                    <input type="text" name="municipio" value="<?php echo isset($datos['municipio']) ? htmlspecialchars($datos['municipio']) : ''; ?>" class="solo-mayus">
                </div>
                <div>
                    <label>Parroquia</label>
                    <input type="text" name="parroquia" value="<?php echo isset($datos['parroquia']) ? htmlspecialchars($datos['parroquia']) : ''; ?>" class="solo-mayus">
                </div>
            </div>

            <div class="fila-doble">
                <div><label>Talla Camisa</label><input type="text" name="talla_camisa" value="<?php echo $datos['talla_camisa']; ?>"></div>
                <div><label>Talla Pantalón</label><input type="text" name="talla_pantalon" value="<?php echo $datos['talla_pantalon']; ?>"></div>
                <div><label>Talla Zapatos</label><input type="text" name="talla_zapatos" value="<?php echo $datos['talla_zapatos']; ?>"></div>
            </div>
            
            <label>Condiciones Médicas / Alergias:</label>
            <div class="checkbox-group">
                <label class="checkbox-item"><input type="checkbox" name="condicion_medica[]" value="Asma" <?php if(strpos($datos['condicion_medica'], 'Asma') !== false) echo 'checked'; ?>> Asma</label>
                <label class="checkbox-item"><input type="checkbox" id="al_check" name="condicion_medica[]" value="Alergia" onchange="toggleCampoExtra('alergia')" <?php if(strpos($datos['condicion_medica'], 'ALERGIA') !== false) echo 'checked'; ?>> Alergia</label>
                <label class="checkbox-item"><input type="checkbox" id="dis_check" name="condicion_medica[]" value="Condición Especial" onchange="toggleCampoExtra('discapacidad')" <?php if(strpos($datos['condicion_medica'], 'ESPECIAL') !== false) echo 'checked'; ?>> Condición Especial</label>
                <label class="checkbox-item"><input type="checkbox" id="ot_check" name="condicion_medica[]" value="Otros" onchange="toggleCampoExtra('otros')" <?php if(strpos($datos['condicion_medica'], 'OTROS') !== false) echo 'checked'; ?>> Otros</label>
            </div>
            
            <input type="text" id="campo_alergia" name="detalle_alergia" class="condicion-extra solo-mayus" placeholder="Especifique alergia" style="<?php echo (strpos($datos['condicion_medica'], 'ALERGIA') !== false) ? 'display:block' : ''; ?>">
            <input type="text" id="campo_discapacidad" name="detalle_discapacidad" class="condicion-extra solo-mayus" placeholder="Especifique condición" style="<?php echo (strpos($datos['condicion_medica'], 'ESPECIAL') !== false) ? 'display:block' : ''; ?>">
            <input type="text" id="campo_otros" name="detalle_otros" class="condicion-extra solo-mayus" placeholder="Otras observaciones" style="<?php echo (strpos($datos['condicion_medica'], 'OTROS') !== false) ? 'display:block' : ''; ?>">
        </div>

        <div class="seccion">
            <h3><i class="fas fa-user-tie"></i> 3. Información del Representante</h3>
            <div id="datos_representante">
                <div class="fila-doble">
                    <div><label>Nombre Completo *</label><input name="nombre_representante" class="solo-letras-mayus" value="<?php echo htmlspecialchars($datos['r_nombre'] ?? ''); ?>"></div>
                    <div><label>Cédula *</label><input name="cedula_representante" class="solo-numeros" value="<?php echo $datos['r_cedula'] ?? ''; ?>"></div>
                </div>
                <div class="fila-doble">
                    <div><label>Teléfono *</label><input name="telefono_representante" class="solo-numeros" value="<?php echo $datos['r_telefono'] ?? ''; ?>"></div>
                    <div><label>Correo Electrónico</label><input type="email" name="correo_representante" value="<?php echo $datos['r_correo'] ?? ''; ?>"></div>
                </div>
                <div class="fila-doble">
                    <div><label>Parentesco / Condición</label><input name="condicion_representante" class="solo-mayus" value="<?php echo $datos['r_condicion'] ?? ''; ?>"></div>
                    <div><label>Profesión *</label><input name="profesion_representante" class="solo-mayus" value="<?php echo $datos['r_profesion'] ?? ''; ?>"></div>
                </div>
                <div class="fila-doble">
                    <div><label>Ocupación *</label><input name="ocupacion_representante" class="solo-mayus" value="<?php echo $datos['r_ocupacion'] ?? ''; ?>"></div>
                    <div><label>Edad *</label><input type="number" name="edad_representante" value="<?php echo $datos['r_edad'] ?? ''; ?>"></div>
                </div>
                <div class="fila-doble">
                    <div><label>Nacionalidad</label><input name="nacionalidad_representante" class="solo-mayus" value="<?php echo $datos['r_nacionalidad'] ?? 'VENEZOLANA'; ?>"></div>
                    <div>
                        <label>Nivel de Instrucción *</label>
                        <select name="nivel_instruccion_representante">
                            <option value="">Seleccione...</option>
                            <option <?php if(($datos['r_instruccion'] ?? '')=='PRIMARIA') echo 'selected'; ?>>PRIMARIA</option>
                            <option <?php if(($datos['r_instruccion'] ?? '')=='SECUNDARIA') echo 'selected'; ?>>SECUNDARIA</option>
                            <option <?php if(($datos['r_instruccion'] ?? '')=='TÉCNICO') echo 'selected'; ?>>TÉCNICO</option>
                            <option <?php if(($datos['r_instruccion'] ?? '')=='UNIVERSITARIO') echo 'selected'; ?>>UNIVERSITARIO</option>
                            <option <?php if(($datos['r_instruccion'] ?? '')=='POSTGRADO') echo 'selected'; ?>>POSTGRADO</option>
                        </select>
                    </div>
                </div>
                <label>Dirección de Habitación *</label>
                <textarea name="direccion_representante" rows="2" class="solo-mayus"><?php echo htmlspecialchars($datos['r_direccion'] ?? ''); ?></textarea>
                <label>Dirección de Trabajo</label>
                <textarea name="direccion_trabajo_representante" rows="2" class="solo-mayus"><?php echo htmlspecialchars($datos['r_trabajo'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="seccion">
            <h3><i class="fas fa-folder-open"></i> 4. Documentos Consignados</h3>
            <div class="checkbox-group">
                <label class="checkbox-item"><input type="checkbox" name="doc_partida_nacimiento" value="1" <?php if($datos['doc_partida_nacimiento']) echo 'checked'; ?>> Partida de Nacimiento</label>
                <label class="checkbox-item"><input type="checkbox" name="doc_copia_cedula_madre" value="1" <?php if($datos['doc_copia_cedula_madre']) echo 'checked'; ?>> Copia Cédula Madre</label>
                <label class="checkbox-item"><input type="checkbox" name="doc_copia_cedula_padre" value="1" <?php if($datos['doc_copia_cedula_padre']) echo 'checked'; ?>> Copia Cédula Padre</label>
                <label class="checkbox-item"><input type="checkbox" name="doc_fotos_carnet" value="1" <?php if($datos['doc_fotos_carnet']) echo 'checked'; ?>> 4 Fotos Carnet</label>
                <label class="checkbox-item"><input type="checkbox" name="doc_certificado_vacunas" value="1" <?php if($datos['doc_certificado_vacunas']) echo 'checked'; ?>> Certificado Vacunas</label>
            </div>
        </div>

        <div class="seccion">
            <h3><i class="fas fa-calendar-check"></i> Datos Administrativos</h3>
            <div class="fila-doble">
                <div>
                    <label>Año Escolar *</label>
                    <input type="text" name="ano_escolar" value="<?php echo $datos['ano_escolar']; ?>" required>
                </div>
                <div>
                    <label>Fecha y Hora de Inscripción</label>
                    <input type="datetime-local" name="fecha_registro_manual" 
                           value="<?php echo isset($datos['fecha_registro']) ? date('Y-m-d\TH:i', strtotime($datos['fecha_registro'])) : date('Y-m-d\TH:i'); ?>" 
                           required>
                </div>
            </div>
        </div>

        <div style="text-align:center; margin-top:30px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
            
            <button type="submit" class="boton" style="background: var(--success); min-width: 200px;">
                <i class="fas fa-save"></i> GUARDAR CAMBIOS
            </button>
            
            <a href="comprobante_pdf.php?id=<?php echo $alumno_id; ?>" target="_blank" class="boton boton-pdf" style="min-width: 200px;">
                <i class="fas fa-file-pdf"></i> GENERAR FICHA PDF
            </a>
            
            <a href="panel_alumnos.php" class="boton" style="background: #6c757d; min-width: 150px;">
                <i class="fas fa-times"></i> CANCELAR
            </a>
        </div>
    </form>
</div>

<script>
// --- INTERFAZ ---
function abrirModal(id) { document.getElementById(id).style.display = 'flex'; }
function cerrarModal(id) { document.getElementById(id).style.display = 'none'; }

function toggleDetalleProcedencia() {
    const val = document.getElementById('lugar_procedencia').value;
    const div = document.getElementById('detalle_procedencia_container');
    if(div) div.style.display = (val === 'Otro') ? 'block' : 'none';
}

function toggleCampoExtra(tipo) {
    let checkId = "";
    if(tipo === 'alergia') checkId = "al_check";
    else if(tipo === 'discapacidad') checkId = "dis_check";
    else if(tipo === 'otros') checkId = "ot_check";
    
    const check = document.getElementById(checkId);
    if(check) {
        document.getElementById('campo_' + tipo).style.display = check.checked ? 'block' : 'none';
    }
}

// --- LÓGICA DE NEGOCIO ---
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){ document.getElementById('img-preview').src = reader.result; }
    reader.readAsDataURL(event.target.files[0]);
}

function calcularEdad() {
    const f = document.getElementById('fecha_nacimiento').value; if(!f) return;
    const n = new Date(f), h = new Date(); let e = h.getFullYear() - n.getFullYear();
    if(h.getMonth() < n.getMonth() || (h.getMonth() == n.getMonth() && h.getDate() < n.getDate())) e--;
    document.getElementById('edad_texto').value = e + " AÑOS";
}

// --- AJAX CARGA DINÁMICA ---
function cargarAulas() {
    const niv = document.getElementById('nivel').value;
    const tur = document.getElementById('turno').value;
    if(!niv || !tur) return;
    
    fetch(`ajax_cargar_aulas.php?nivel=${niv}&turno=${tur}`)
        .then(res => res.json())
        .then(data => {
            const s = document.getElementById('select_aula');
            s.innerHTML = '<option value="">-- Seleccionar Aula --</option>';
            data.forEach(a => s.innerHTML += `<option value="${a.id}">${a.nombre_grupo}</option>`);
            // Limpiar sección al cambiar aula
            document.getElementById('select_seccion').innerHTML = '<option value="">-- Seleccionar Sección --</option>';
        });
}

function cargarSecciones() {
    const aid = document.getElementById('select_aula').value;
    if(!aid) return;
    
    fetch(`ajax_cargar_secciones.php?aula_id=${aid}`)
        .then(res => res.json())
        .then(data => {
            const s = document.getElementById('select_seccion');
            s.innerHTML = '<option value="">-- Seleccionar Sección --</option>';
            data.forEach(sec => s.innerHTML += `<option value="${sec.id}">${sec.letra}</option>`);
        });
}

// --- AJAX GUARDADO RÁPIDO (NUEVAS AULAS/SECCIONES) ---
function guardarAula() {
    const nom = document.getElementById('new_nombre_aula').value;
    const niv = document.getElementById('nivel').value;
    const tur = document.getElementById('turno').value;
    if(!nom || !niv || !tur) return Swal.fire('Atención', 'Seleccione Nivel y Turno primero.', 'warning');

    const f = new FormData(); f.append('nombre', nom); f.append('nivel', niv); f.append('turno', tur);
    fetch('ajax_guardar_aula.php', { method: 'POST', body: f })
        .then(res => res.json()).then(d => { 
            if(d.success) { 
                Swal.fire('Éxito', 'Aula creada', 'success'); 
                cargarAulas(); 
                cerrarModal('modalAula'); 
            } else {
                Swal.fire('Error', d.message || 'No se pudo guardar', 'error');
            }
        }).catch(err => Swal.fire('Error', 'Error de conexión', 'error'));
}

function guardarSeccion() {
    const letra = document.getElementById('new_letra_seccion').value;
    const aid = document.getElementById('select_aula').value;
    const niv = document.getElementById('nivel').value;
    const tur = document.getElementById('turno').value;
    if(!letra || !aid) return Swal.fire('Atención', 'Seleccione el Aula primero.', 'warning');

    const f = new FormData(); f.append('letra', letra); f.append('aula_id', aid); f.append('nivel', niv); f.append('turno', tur);
    fetch('ajax_guardar_seccion.php', { method: 'POST', body: f })
        .then(res => res.json()).then(d => { 
            if(d.success) { 
                Swal.fire('Éxito', 'Sección creada', 'success'); 
                cargarSecciones(); 
                cerrarModal('modalSeccion'); 
            } else {
                Swal.fire('Error', d.message || 'No se pudo guardar', 'error');
            }
        }).catch(err => Swal.fire('Error', 'Error de conexión', 'error'));
}

// VALIDACIONES EN TIEMPO REAL
document.body.addEventListener('input', e => {
    if(e.target.classList.contains('solo-mayus')) e.target.value = e.target.value.toUpperCase();
    if(e.target.classList.contains('solo-letras-mayus')) e.target.value = e.target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '').toUpperCase();
    if(e.target.classList.contains('solo-numeros')) e.target.value = e.target.value.replace(/\D/g,'');
});
</script>
</body>
</html>