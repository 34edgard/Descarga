<?php include 'includes/conexion.php'; 
// Configurar zona horaria
date_default_timezone_set('America/Caracas'); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripción Profesional - SIPRE</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; --success: #28a745; --warning: #f39c12; --gray: #6c757d; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); padding: 20px; min-height: 100vh; margin: 0; }
        
        /* Contenedor Principal */
        .formulario { background: #fff; max-width: 950px; margin: auto; padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); position: relative; }
        
        /* Botón Volver */
        .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .btn-volver { background: var(--gray); color: white; text-decoration: none; padding: 8px 15px; border-radius: 8px; font-weight: bold; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; }
        .btn-volver:hover { background: #5a6268; transform: translateX(-3px); }

        .seccion { background: #fdfdfd; padding: 25px; margin-bottom: 25px; border-radius: 12px; border: 1px solid #eee; border-left: 5px solid var(--primary); }
        h2 { text-align: center; color: #333; text-transform: uppercase; margin: 0; letter-spacing: 1px; flex-grow: 1; }
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
        <button type="button" onclick="cerrarModal('modalAula')" style="width:100%; margin-top:10px; background:none; border:none; color:red; cursor:pointer; width:100%;">Cancelar</button>
    </div>
</div>

<div id="modalSeccion" class="modal">
    <div class="modal-content">
        <h3><i class="fas fa-layer-group"></i> Nueva Sección</h3>
        <label>Letra de la Sección (Ej: B):</label>
        <input type="text" id="new_letra_seccion" maxlength="1" class="solo-mayus">
        <button type="button" class="boton" style="width:100%; margin-top:15px; background:var(--success);" onclick="guardarSeccion()">Guardar Sección</button>
        <button type="button" onclick="cerrarModal('modalSeccion')" style="width:100%; margin-top:10px; background:none; border:none; color:red; cursor:pointer; width:100%;">Cancelar</button>
    </div>
</div>

<div class="formulario">
    
    <div class="header-nav">
        <a href="panel_alumnos.php" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Volver al Menú
        </a>
        <h2><i class="fas fa-graduation-cap"></i> Inscripción SIPRE</h2>
        <div style="width: 100px;"></div> </div>

    <form action="procesar_inscripcion.php" method="POST" enctype="multipart/form-data">
        
        <div style="text-align:center; margin-bottom:20px;">
            <img src="uploads/default.png" id="img-preview" class="preview-circular" onerror="this.src='https://via.placeholder.com/150'">
            <label for="foto_alumno" class="boton" style="padding: 8px 15px; font-size: 13px; cursor:pointer;"><i class="fas fa-camera"></i> Cargar Foto</label>
            <input type="file" name="foto_alumno" id="foto_alumno" style="display:none" onchange="previewImage(event)">
        </div>

        <div class="seccion" style="border-left-color: var(--warning);">
            <h3><i class="fas fa-university"></i> 1. Ubicación Académica</h3>
            <div class="fila-doble">
                <div>
                    <label>Nivel *</label>
                    <select name="nivel" id="nivel" required onchange="cargarAulas()">
                        <option value="">Seleccione...</option>
                        <option value="1°">1° NIVEL</option>
                        <option value="2°">2° NIVEL</option>
                        <option value="3°">3° NIVEL</option>
                    </select>
                </div>
                <div>
                    <label>Turno *</label>
                    <select name="turno" id="turno" required onchange="cargarAulas()">
                        <option value="">Seleccione...</option>
                        <option value="Mañana">MAÑANA</option>
                        <option value="Tarde">TARDE</option>
                    </select>
                </div>
            </div>
            <div class="fila-doble">
                <div style="flex: 8;">
                    <label>Aula / Grupo *</label>
                    <select name="aula_id" id="select_aula" required onchange="cargarSecciones()">
                        <option value="">-- Primero elija Nivel y Turno --</option>
                    </select>
                </div>
                <div style="flex: 1;"><button type="button" class="btn-plus" onclick="abrirModal('modalAula')"><i class="fas fa-plus"></i></button></div>
            </div>
            <div class="fila-doble">
                <div style="flex: 8;">
                    <label>Sección *</label>
                    <select name="seccion_id" id="select_seccion" required>
                        <option value="">-- Primero elija Aula --</option>
                    </select>
                </div>
                <div style="flex: 1;"><button type="button" class="btn-plus" onclick="abrirModal('modalSeccion')"><i class="fas fa-plus"></i></button></div>
            </div>
            <label>Docente Guía *</label>
            <select name="docente_id" required>
                <option value="">Seleccione Docente...</option>
                <?php 
                $res_doc = mysqli_query($conexion, "SELECT id, nombre FROM docentes");
                while($d = mysqli_fetch_assoc($res_doc)) echo "<option value='{$d['id']}'>{$d['nombre']}</option>";
                ?>
            </select>
        </div>

        <div class="seccion">
            <h3><i class="fas fa-child"></i> 2. Información del Alumno</h3>
            <label>Nombre Completo del Estudiante *</label>
            <input type="text" name="nombre_alumno" class="solo-letras-mayus" required>

            <div class="fila-doble">
                <div><label>Número de hijo *</label><input type="number" name="numero_hijo" id="numero_hijo" min="1" value="1" required onchange="generarCedulaEscolar()"></div>
                <div><label>Cédula Escolar (Auto)</label><input type="text" id="cedula_escolar" readonly style="background:#eee; font-weight:bold;">
                <input type="hidden" name="cedula_escolar" id="cedula_escolar_hidden"></div>
            </div>

            <div class="fila-doble">
                <div><label>Fecha de Nacimiento *</label><input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required onchange="calcularEdad(); generarCedulaEscolar()"></div>
                <div><label>Edad Calculada</label><input type="text" id="edad_texto" readonly style="background:#eee">
                <input type="hidden" name="edad" id="edad_val"></div>
            </div>

            <div class="fila-doble">
                <div><label>Sexo *</label><select name="sexo" required><option value="">Seleccione...</option><option value="M">MASCULINO</option><option value="F">FEMENINO</option></select></div>
                <div>
                    <label>Lugar de Procedencia *</label>
                    <select name="lugar_procedencia" id="lugar_procedencia" required onchange="toggleDetalleProcedencia()">
                        <option value="Hogar">HOGAR</option><option value="Colegio">COLEGIO</option><option value="Casa Hogar">CASA HOGAR</option><option value="Otro">OTRO</option>
                    </select>
                </div>
            </div>
            <div id="detalle_procedencia_container" class="condicion-extra">
                <label>Especifique Procedencia</label>
                <input type="text" name="detalle_procedencia" class="solo-mayus">
            </div>

            <label>Dirección de Residencia del Niño *</label>
            <textarea name="direccion_nino" rows="2" class="solo-mayus" required></textarea>

            <div class="fila-doble">
                <div><label>Nacionalidad</label><input type="text" name="nacionalidad" value="VENEZOLANA" class="solo-mayus"></div>
                <div><label>Municipio</label><input type="text" name="municipio" value="MATURÍN" class="solo-mayus"></div>
            </div>
            <div class="fila-doble">
                <div><label>Parroquia</label><input type="text" name="parroquia" class="solo-mayus"></div>
                <div><label>Estado</label><input type="text" name="estado" value="MONAGAS" readonly></div>
            </div>

            <div class="fila-doble">
                <div><label>Talla Camisa</label><input type="text" name="talla_camisa" placeholder="Ej: 6"></div>
                <div><label>Talla Pantalón</label><input type="text" name="talla_pantalon" placeholder="Ej: 8"></div>
                <div><label>Talla Zapatos</label><input type="text" name="talla_zapatos" placeholder="Ej: 28"></div>
            </div>
            
            <label>Condiciones Médicas / Alergias:</label>
            <div class="checkbox-group">
                <label class="checkbox-item"><input type="checkbox" name="condicion_medica[]" value="Asma"> Asma</label>
                <label class="checkbox-item"><input type="checkbox" id="al_check" name="condicion_medica[]" value="Alergia" onchange="toggleCampoExtra('alergia')"> Alergia</label>
                <label class="checkbox-item"><input type="checkbox" id="dis_check" name="condicion_medica[]" value="Condición Especial" onchange="toggleCampoExtra('discapacidad')"> Condición Especial</label>
                <label class="checkbox-item"><input type="checkbox" id="ot_check" name="condicion_medica[]" value="Otros" onchange="toggleCampoExtra('otros')"> Otros</label>
            </div>
            <input type="text" id="campo_alergia" name="detalle_alergia" class="condicion-extra solo-mayus" placeholder="¿Qué tipo de alergia?">
            <input type="text" id="campo_discapacidad" name="detalle_discapacidad" class="condicion-extra solo-mayus" placeholder="¿Qué tipo de condición especial?">
            <input type="text" id="campo_otros" name="detalle_otros" class="condicion-extra solo-mayus" placeholder="Especifique condición médica">
        </div>

        <div class="seccion">
            <h3><i class="fas fa-user-tie"></i> 3. Información del Representante</h3>
            <button type="button" class="boton boton-rep" onclick="toggleRepresentante()">
                <i class="fas fa-user-edit"></i> Gestionar Datos del Representante
            </button>

            <div id="datos_representante" style="display:none; margin-top:20px;">
                <div class="fila-doble">
                    <div><label>Nombre Completo *</label><input name="nombre_representante" class="solo-letras-mayus" onchange="generarCedulaEscolar()"></div>
                    <div><label>Cédula *</label><input name="cedula_representante" id="cedula_rep" class="solo-numeros" maxlength="10" required onchange="generarCedulaEscolar()"></div>
                </div>
                <div class="fila-doble">
                    <div><label>Teléfono *</label><input name="telefono_representante" class="solo-numeros" placeholder="04XXXXXXXXX"></div>
                    <div><label>Correo Electrónico</label><input type="email" name="correo_representante" placeholder="ejemplo@correo.com"></div>
                </div>
                <div class="fila-doble">
                    <div><label>Parentesco / Condición</label><input name="condicion_representante" class="solo-mayus" placeholder="Ej: MADRE, PADRE"></div>
                    <div><label>Profesión *</label><input name="profesion_representante" class="solo-mayus"></div>
                </div>
                <div class="fila-doble">
                    <div><label>Ocupación *</label><input name="ocupacion_representante" class="solo-mayus"></div>
                    <div><label>Edad *</label><input type="number" name="edad_representante" min="18"></div>
                </div>
                <div class="fila-doble">
                    <div><label>Nacionalidad</label><input name="nacionalidad_representante" class="solo-mayus" value="VENEZOLANA"></div>
                    <div>
                        <label>Nivel de Instrucción *</label>
                        <select name="nivel_instruccion_representante">
                            <option value="">Seleccione...</option>
                            <option>PRIMARIA</option><option>SECUNDARIA</option><option>TÉCNICO</option><option>UNIVERSITARIO</option><option>POSTGRADO</option>
                        </select>
                    </div>
                </div>
                <label>Dirección de Habitación *</label>
                <textarea name="direccion_representante" rows="2" class="solo-mayus"></textarea>
                <label>Dirección de Trabajo</label>
                <textarea name="direccion_trabajo_representante" rows="2" class="solo-mayus"></textarea>
            </div>
        </div>

        <div class="seccion">
            <h3><i class="fas fa-folder-open"></i> 4. Documentos Consignados</h3>
            <div class="checkbox-group">
                <label class="checkbox-item"><input type="checkbox" name="doc_partida_nacimiento" value="1"> Partida de Nacimiento</label>
                <label class="checkbox-item"><input type="checkbox" name="doc_copia_cedula_madre" value="1"> Copia Cédula Madre</label>
                <label class="checkbox-item"><input type="checkbox" name="doc_copia_cedula_padre" value="1"> Copia Cédula Padre</label>
                <label class="checkbox-item"><input type="checkbox" name="doc_fotos_carnet" value="1"> 4 Fotos Carnet</label>
                <label class="checkbox-item"><input type="checkbox" name="doc_certificado_vacunas" value="1"> Certificado Vacunas</label>
            </div>
        </div>

        <div class="seccion">
            <h3><i class="fas fa-calendar-check"></i> Datos Administrativos</h3>
            <div class="fila-doble">
                <div>
                    <label>Año Escolar *</label>
                    <input type="text" name="ano_escolar" value="2025-2026" required>
                </div>
                <div>
                    <label>Fecha y Hora de Registro</label>
                    <input type="datetime-local" name="fecha_registro_manual" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                </div>
            </div>
        </div>

        <div style="text-align:center; margin-top:30px;">
            <button type="submit" class="boton" style="background: var(--success); width: 100%; justify-content:center; padding: 20px;">
                <i class="fas fa-save"></i> GUARDAR INSCRIPCIÓN Y GENERAR PDF
            </button>
        </div>
    </form>
</div>

<script>
// --- INTERFAZ ---
function abrirModal(id) { document.getElementById(id).style.display = 'flex'; }
function cerrarModal(id) { document.getElementById(id).style.display = 'none'; }

function toggleRepresentante() {
    const div = document.getElementById('datos_representante');
    div.style.display = (div.style.display === 'none') ? 'block' : 'none';
}

function toggleDetalleProcedencia() {
    const val = document.getElementById('lugar_procedencia').value;
    document.getElementById('detalle_procedencia_container').style.display = (val === 'Otro') ? 'block' : 'none';
}

function toggleCampoExtra(tipo) {
    let checkId = "";
    if(tipo === 'alergia') checkId = "al_check";
    else if(tipo === 'discapacidad') checkId = "dis_check";
    else if(tipo === 'otros') checkId = "ot_check";
    
    const isChecked = document.getElementById(checkId).checked;
    document.getElementById('campo_' + tipo).style.display = isChecked ? 'block' : 'none';
}

// --- AJAX CARGAR (AULAS Y SECCIONES) ---
function cargarAulas() {
    const niv = document.getElementById('nivel').value;
    const tur = document.getElementById('turno').value;
    if(!niv || !tur) return;
    fetch(`ajax_cargar_aulas.php?nivel=${niv}&turno=${tur}`)
        .then(res => res.json()).then(data => {
            const s = document.getElementById('select_aula');
            s.innerHTML = '<option value="">-- Seleccionar Aula --</option>';
            data.forEach(a => s.innerHTML += `<option value="${a.id}">${a.nombre_grupo}</option>`);
        });
}

function cargarSecciones() {
    const aid = document.getElementById('select_aula').value;
    if(!aid) return;
    fetch(`ajax_cargar_secciones.php?aula_id=${aid}`)
        .then(res => res.json()).then(data => {
            const s = document.getElementById('select_seccion');
            s.innerHTML = '<option value="">-- Seleccionar Sección --</option>';
            data.forEach(sec => s.innerHTML += `<option value="${sec.id}">${sec.letra}</option>`);
        });
}

// --- AJAX GUARDAR (NUEVAS AULAS Y SECCIONES) ---
function guardarAula() {
    const nom = document.getElementById('new_nombre_aula').value;
    const niv = document.getElementById('nivel').value;
    const tur = document.getElementById('turno').value;
    if(!nom || !niv || !tur) return alert("Seleccione Nivel y Turno en el formulario primero.");

    const f = new FormData(); f.append('nombre', nom); f.append('nivel', niv); f.append('turno', tur);
    fetch('ajax_guardar_aula.php', { method: 'POST', body: f })
        .then(res => res.json()).then(d => { 
            if(d.success) { Swal.fire('Éxito', 'Aula creada', 'success'); cargarAulas(); cerrarModal('modalAula'); } 
        });
}

function guardarSeccion() {
    const letra = document.getElementById('new_letra_seccion').value;
    const aid = document.getElementById('select_aula').value;
    const niv = document.getElementById('nivel').value;
    const tur = document.getElementById('turno').value;
    if(!letra || !aid) return alert("Seleccione el Aula primero.");

    const f = new FormData(); f.append('letra', letra); f.append('aula_id', aid); f.append('nivel', niv); f.append('turno', tur);
    fetch('ajax_guardar_seccion.php', { method: 'POST', body: f })
        .then(res => res.json()).then(d => { 
            if(d.success) { Swal.fire('Éxito', 'Sección creada', 'success'); cargarSecciones(); cerrarModal('modalSeccion'); } 
        });
}

// --- LÓGICA DE NEGOCIO (EDAD, FOTO) ---
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
    document.getElementById('edad_val').value = e;
}

// --- GENERACIÓN DE CÉDULA ESCOLAR (Representante + Año + Hijo) ---
function generarCedulaEscolar() {
    const num = document.getElementById('numero_hijo').value;
    const fec = document.getElementById('fecha_nacimiento').value;
    const ced = document.getElementById('cedula_rep').value;
    
    if (num && fec && ced) {
        const cedulaLimpia = ced.replace(/\D/g, '');
        const anioDosDigitos = fec.substring(2, 4); 
        const resultado = cedulaLimpia + anioDosDigitos + num;
        
        document.getElementById('cedula_escolar').value = resultado;
        document.getElementById('cedula_escolar_hidden').value = resultado;
    }
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