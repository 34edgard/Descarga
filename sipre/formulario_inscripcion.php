<?php include 'includes/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario de Inscripción</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      margin: 0;
      padding: 20px;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .formulario {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      max-width: 700px;
      width: 100%;
    }
    
    .formulario h2 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
      font-size: 24px;
      border-bottom: 2px solid #667eea;
      padding-bottom: 10px;
    }
    
    .seccion {
      margin: 25px 0;
      padding: 20px;
      background: #f8f9fa;
      border-radius: 10px;
      border-left: 4px solid #667eea;
    }
    
    .seccion h3 {
      color: #333;
      margin-bottom: 15px;
      font-size: 18px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    input[type="text"],
    input[type="date"],
    input[type="number"],
    input[type="email"],
    input[type="file"],
    select,
    textarea {
      width: 100%;
      padding: 12px;
      margin: 8px 0 15px 0;
      border: 2px solid #e1e1e1;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }
    
    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    /* SOLO los campos condicionales están ocultos por defecto */
    .condicion-extra {
      margin-top: 10px;
      margin-bottom: 15px;
      display: none;
      animation: fadeIn 0.3s ease;
    }
    
    /* El formulario de representante NO tiene display:none aquí */
    #form_representante {
      margin-top: 10px;
      margin-bottom: 15px;
      animation: fadeIn 0.3s ease;
    }
    
    #edad, #cedula_escolar {
      background-color: #f8f9fa;
      color: #666;
      font-weight: bold;
    }
    
    .fila-doble {
      display: flex;
      gap: 15px;
      margin-bottom: 15px;
    }
    
    .fila-doble select,
    .fila-doble input {
      flex: 1;
      padding: 12px;
      border-radius: 8px;
      border: 2px solid #e1e1e1;
      background: white;
    }
    
    label {
      display: block;
      margin-top: 15px;
      margin-bottom: 8px;
      font-weight: bold;
      color: #444;
      font-size: 14px;
    }
    
    .checkbox-group {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      margin: 10px 0;
      border: 1px solid #e9ecef;
    }
    
    .checkbox-item {
      margin: 10px 0;
      display: flex;
      align-items: center;
    }
    
    .checkbox-item input[type="checkbox"] {
      margin-right: 10px;
      transform: scale(1.1);
    }
    
    .centrar-botones {
      text-align: center;
      margin: 25px 0 10px 0;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .campo-requerido::after {
      content: " *";
      color: #e74c3c;
    }
    
    .mensaje-edad {
      font-size: 12px;
      color: #666;
      margin-top: -10px;
      margin-bottom: 15px;
      font-style: italic;
    }
    
    .documentos-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin: 15px 0;
    }

    /* MODALES */
    .modal-backdrop {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
    }

    .modal-content {
      background: white;
      width: 90%;
      max-width: 500px;
      margin: 50px auto;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.3);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #667eea;
    }

    .modal-header h3 {
      margin: 0;
      color: #333;
    }

    .close-modal {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #666;
    }

    .close-modal:hover {
      color: #333;
    }

    .link-nueva-aula {
      color: #667eea;
      cursor: pointer;
      text-decoration: underline;
      font-size: 13px;
    }

    .link-nueva-aula:hover {
      color: #5a6fd8;
    }
    
    .modal-confirmacion {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1001;
    }

    .modal-confirmacion-content {
      background: white;
      width: 90%;
      max-width: 400px;
      margin: 100px auto;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.3);
      text-align: center;
    }

    .modal-confirmacion h3 {
      margin-top: 0;
      color: #333;
      border-bottom: 2px solid #667eea;
      padding-bottom: 10px;
    }

    .modal-confirmacion p {
      margin: 20px 0;
      color: #555;
      font-size: 16px;
    }

    .modal-confirmacion-botones {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 25px;
    }

    /* ========== ESTILOS FORZADOS SOLO PARA BOTONES ========== */
    .boton {
      padding: 14px 28px !important;
      background: #667eea !important;
      color: white !important;
      border: 3px solid #5a6fd8 !important;
      border-radius: 10px !important;
      cursor: pointer !important;
      font-size: 16px !important;
      font-weight: bold !important;
      transition: all 0.3s ease !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 10px !important;
      text-decoration: none !important;
      min-width: 170px !important;
      margin: 8px !important;
      justify-content: center !important;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
      visibility: visible !important;
      opacity: 1 !important;
      position: relative !important;
      z-index: 1000 !important;
    }
    
    .boton:hover {
      background: #5a6fd8 !important;
      transform: translateY(-3px) !important;
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4) !important;
    }
    
    .boton-secundario {
      background: #6c757d !important;
      border-color: #5a6268 !important;
    }
    
    .boton-secundario:hover {
      background: #5a6268 !important;
    }
    
    .boton-peligro {
      background: #e74c3c !important;
      border-color: #c0392b !important;
    }
    
    .boton-peligro:hover {
      background: #c0392b !important;
    }
    
    .boton-info {
      background: #17a2b8 !important;
      border-color: #138496 !important;
    }
    
    .boton-info:hover {
      background: #138496 !important;
    }
    
    .grupo-botones {
      display: flex !important;
      justify-content: center !important;
      align-items: center !important;
      gap: 15px !important;
      flex-wrap: wrap !important;
      margin: 35px 0 20px 0 !important;
      padding: 25px !important;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
      border-radius: 15px !important;
      border: 3px solid #667eea !important;
      visibility: visible !important;
      opacity: 1 !important;
      position: relative !important;
      z-index: 1000 !important;
    }
    
    /* BOTÓN AGREGAR REPRESENTANTE ESPECÍFICO */
    #btnAgregarRepresentante {
      background: #28a745 !important;
      border-color: #218838 !important;
      font-size: 16px !important;
      padding: 16px 32px !important;
      min-width: 220px !important;
    }
    
    #btnAgregarRepresentante:hover {
      background: #218838 !important;
      transform: scale(1.05) !important;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      .formulario {
        max-width: 95%;
        padding: 20px;
      }
      
      .fila-doble {
        flex-direction: column;
        gap: 0;
      }
      
      .documentos-grid {
        grid-template-columns: 1fr;
      }

      .modal-content {
        width: 95%;
        margin: 20px auto;
        padding: 15px;
      }
      
      .grupo-botones {
        flex-direction: column;
        gap: 12px !important;
      }
      
      .grupo-botones .boton {
        width: 100% !important;
        max-width: 300px !important;
        min-width: auto !important;
      }
      
      .modal-confirmacion-botones {
        flex-direction: column;
        align-items: center;
      }
      
      .modal-confirmacion-botones .boton {
        width: 100%;
        max-width: 200px;
      }
    }
  </style>
</head>
<body>
  <div class="formulario">
    <h2><i class="fas fa-graduation-cap"></i> Inscripción Preescolar</h2>
    <form action="procesar_inscripcion.php" method="POST" enctype="multipart/form-data">
      
      <!-- SECCIÓN INFORMACIÓN DEL ALUMNO (COMPLETA) -->
      <div class="seccion">
        <h3><i class="fas fa-child"></i> Información del Alumno</h3>
        
        <label class="campo-requerido">Nombre del alumno:</label>
        <input type="text" name="nombre_alumno" placeholder="Ingrese el nombre completo" required>
        
        <!-- CÉDULA ESCOLAR AUTOMÁTICA - OCULTA -->
        <input type="hidden" name="cedula_escolar" id="cedula_escolar_hidden">
        
        <label>Cédula escolar generada:</label>
        <input type="text" id="cedula_escolar" placeholder="Se generará automáticamente" readonly>
        <div class="mensaje-edad">La cédula escolar se genera automáticamente al completar los datos</div>

        <label class="campo-requerido">Número de hijo:</label>
        <input type="number" name="numero_hijo" id="numero_hijo" placeholder="Ej: 1 (primer hijo), 2 (segundo hijo)" min="1" max="20" required onchange="generarCedulaEscolar()">

        <label class="campo-requerido">Fecha de nacimiento:</label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required onchange="calcularEdad(); generarCedulaEscolar()">
        
        <label>Edad calculada:</label>
        <input type="number" name="edad" id="edad" placeholder="Se calculará automáticamente" readonly>
        <div class="mensaje-edad">La edad se calcula automáticamente al seleccionar la fecha de nacimiento</div>

        <div class="fila-doble">
          <div style="flex: 1;">
            <label class="campo-requerido">Sexo:</label>
            <select name="sexo" required>
              <option value="">Seleccione...</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select>
          </div>

          <div style="flex: 1;">
            <label class="campo-requerido">Nivel:</label>
            <select name="nivel" id="nivel" required onchange="cargarAulas()">
              <option value="">Seleccione...</option>
              <option value="1°">1° Nivel</option>
              <option value="2°">2° Nivel</option>
              <option value="3°">3° Nivel</option>
            </select>
          </div>
        </div>

        <!-- SECCIÓN TURNO Y AULA -->
        <div class="fila-doble">
          <div style="flex: 1;">
            <label class="campo-requerido">Turno:</label>
            <select name="turno" id="turno" required onchange="cargarAulas()">
              <option value="">Seleccione turno</option>
              <option value="Mañana">Mañana - GRUPO A</option>
              <option value="Tarde">Tarde - GRUPO B</option>
            </select>
          </div>

          <div style="flex: 1;">
            <label class="campo-requerido">Aula/Sección:</label>
            <select name="aula_id" id="aula_id" required>
              <option value="">Seleccione turno y nivel</option>
            </select>
            <div style="margin-top: 5px;">
              <span class="link-nueva-aula" onclick="mostrarModalNuevaAula()">
                <i class="fas fa-plus"></i> Crear nueva sección
              </span>
            </div>
          </div>
        </div>

        <label class="campo-requerido">Lugar de procedencia:</label>
        <select name="lugar_procedencia" id="lugar_procedencia" required onchange="toggleDetalleProcedencia()">
          <option value="">Seleccione...</option>
          <option value="Hogar">Hogar</option>
          <option value="Colegio">Colegio</option>
          <option value="Casa Hogar">Casa Hogar</option>
          <option value="Otro">Otro</option>
        </select>
        
        <div id="detalle_procedencia_container" class="condicion-extra">
          <input type="text" name="detalle_procedencia" placeholder="Especifique el lugar de procedencia">
        </div>

        <label class="campo-requerido">Dirección del niño:</label>
        <textarea name="direccion_nino" placeholder="Dirección completa de residencia del niño" rows="3" required></textarea>

        <div class="fila-doble">
          <div style="flex: 1;">
            <label class="campo-requerido">Nacionalidad:</label>
            <input type="text" name="nacionalidad" placeholder="Ej: Venezolana" required>
          </div>
          <div style="flex: 1;">
            <label class="campo-requerido">Municipio:</label>
            <input type="text" name="municipio" placeholder="Ej: Maturín" required>
          </div>
        </div>

        <label class="campo-requerido">Estado:</label>
        <input type="text" name="estado" value="Monagas" readonly required>

        <div class="fila-doble">
          <div style="flex: 1;">
            <label class="campo-requerido">Talla de camisa:</label>
            <input type="text" name="talla_camisa" placeholder="Ej: 6" required>
          </div>
          <div style="flex: 1;">
            <label class="campo-requerido">Talla de pantalón:</label>
            <input type="text" name="talla_pantalon" placeholder="Ej: 6" required>
          </div>
        </div>

        <div class="fila-doble">
          <div style="flex: 1;">
            <label class="campo-requerido">Talla de zapatos:</label>
            <input type="text" name="talla_zapatos" placeholder="Ej: 28" required>
          </div>
          <div style="flex: 1;">
            <label class="campo-requerido">Año escolar:</label>
            <input type="text" name="ano_escolar" placeholder="Ej: 2024-2025" required>
          </div>
        </div>

        <label>Condiciones médicas:</label>
        <div class="checkbox-group">
          <div class="checkbox-item">
            <input type="checkbox" name="condicion_medica[]" value="Asma"> Asma
          </div>

          <div class="checkbox-item">
            <input type="checkbox" id="alergia_check" name="condicion_medica[]" value="Alergia" onchange="toggleCampo('alergia')"> Alergia
          </div>
          <div id="campo_alergia" class="condicion-extra">
            <input type="text" name="detalle_alergia" placeholder="¿Qué tipo de alergia?" />
          </div>

          <div class="checkbox-item">
            <input type="checkbox" id="discapacidad_check" name="condicion_medica[]" value="Condición Especial" onchange="toggleCampo('discapacidad')"> Condición Especial
          </div>
          <div id="campo_discapacidad" class="condicion-extra">
            <input type="text" name="detalle_discapacidad" placeholder="¿Qué tipo de Condición Especial?" />
          </div>

          <!-- NUEVO CHECKBOX "OTROS" -->
          <div class="checkbox-item">
            <input type="checkbox" id="otros_check" name="condicion_medica[]" value="Otros" onchange="toggleCampo('otros')"> Otros
          </div>
          <div id="campo_otros" class="condicion-extra">
            <input type="text" name="detalle_otros" placeholder="Especifique la condición médica" />
          </div>
        </div>

        <label>Foto del alumno:</label>
        <input type="file" name="foto_alumno" accept="image/*">
      </div>

      <!-- SECCIÓN INFORMACIÓN DEL REPRESENTANTE -->
      <div class="seccion">
        <h3><i class="fas fa-users"></i> Información del Representante</h3>
        
        <label>Representante:</label>
        <div class="centrar-botones">
          <button type="button" class="boton" onclick="mostrarFormularioRepresentante()" id="btnAgregarRepresentante" style="background: #28a745 !important; border-color: #218838 !important;">
            <i class="fas fa-user-plus"></i> Agregar representante
          </button>
        </div>
        
        <!-- FORMULARIO DE REPRESENTANTE - INICIALMENTE OCULTO POR JAVASCRIPT -->
        <div id="form_representante">
          <label class="campo-requerido">Nombre completo:</label>
          <input type="text" name="nombre_representante" placeholder="Nombre del representante" required onchange="generarCedulaEscolar()">
          
          <label class="campo-requerido">Cédula:</label>
          <input type="text" name="cedula_representante" id="cedula_representante" placeholder="Número de cédula" required onchange="generarCedulaEscolar()">
          
          <label class="campo-requerido">Teléfono:</label>
          <input type="text" name="telefono_representante" placeholder="Número de teléfono" required>
          
          <label>Correo electrónico:</label>
          <input type="email" name="correo_representante" placeholder="correo@ejemplo.com">

          <label class="campo-requerido">Dirección donde vive:</label>
          <textarea name="direccion_representante" placeholder="Dirección completa del representante" rows="3" required></textarea>

          <label>Dirección de trabajo:</label>
          <textarea name="direccion_trabajo_representante" placeholder="Dirección del lugar de trabajo" rows="2"></textarea>

          <div class="fila-doble">
            <div style="flex: 1;">
              <label class="campo-requerido">Profesión:</label>
              <input type="text" name="profesion_representante" placeholder="Ej: Ingeniero" required>
            </div>
            <div style="flex: 1;">
              <label class="campo-requerido">Edad:</label>
              <input type="number" name="edad_representante" placeholder="Edad" min="18" max="99" required>
            </div>
          </div>

          <div class="fila-doble">
            <div style="flex: 1;">
              <label class="campo-requerido">Nacionalidad:</label>
              <input type="text" name="nacionalidad_representante" placeholder="Ej: Venezolana" required>
            </div>
            <div style="flex: 1;">
              <label class="campo-requerido">Nivel de instrucción:</label>
              <select name="nivel_instruccion_representante" required>
                <option value="">Seleccione...</option>
                <option value="Primaria">Primaria</option>
                <option value="Secundaria">Secundaria</option>
                <option value="Técnico">Técnico</option>
                <option value="Universitario">Universitario</option>
                <option value="Postgrado">Postgrado</option>
              </select>
            </div>
          </div>

          <label class="campo-requerido">Ocupación:</label>
          <input type="text" name="ocupacion_representante" placeholder="Ej: Empleado público" required>
        </div>
      </div>

      <!-- SECCIÓN DOCUMENTOS -->
      <div class="seccion">
        <h3><i class="fas fa-folder-open"></i> Documentos Consignados</h3>
        <div class="documentos-grid">
          <div class="checkbox-item">
            <input type="checkbox" name="doc_partida_nacimiento" value="1"> Partida de Nacimiento
          </div>
          <div class="checkbox-item">
            <input type="checkbox" name="doc_copia_cedula_madre" value="1"> Copia Cédula Madre
          </div>
          <div class="checkbox-item">
            <input type="checkbox" name="doc_copia_cedula_padre" value="1"> Copia Cédula Padre
          </div>
          <div class="checkbox-item">
            <input type="checkbox" name="doc_fotos_carnet" value="1"> 4 Fotos Tipo Carnet
          </div>
          <div class="checkbox-item">
            <input type="checkbox" name="doc_certificado_vacunas" value="1"> Copia Certificado de Vacunas
          </div>
        </div>
      </div>

      <!-- SECCIÓN DOCENTE -->
      <div class="seccion">
        <h3><i class="fas fa-chalkboard-teacher"></i> Asignación de Docente</h3>
        <label class="campo-requerido">Docente:</label>
        <select name="docente_id" required>
          <option value="">Seleccione docente</option>
          <?php
            $resultado = mysqli_query($conexion, "SELECT id, nombre FROM docentes");
            while ($fila = mysqli_fetch_assoc($resultado)) {
              echo "<option value='{$fila['id']}'>{$fila['nombre']}</option>";
            }
          ?>
        </select>
      </div>

      <!-- BOTONES PRINCIPALES - 100% VISIBLES -->
      <div class="grupo-botones">
        <button type="submit" class="boton">
          <i class="fas fa-save"></i> Guardar inscripción
        </button>
        <button type="reset" class="boton boton-secundario">
          <i class="fas fa-undo"></i> Limpiar formulario
        </button>
        <a href="panel_alumnos.php" class="boton boton-info">
          <i class="fas fa-home"></i> Menú Principal
        </a>
        <button type="button" class="boton boton-peligro" onclick="mostrarConfirmacionCerrarSesion()">
          <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </button>
      </div>
    </form>
  </div>

  <!-- MODALES (se mantienen igual) -->
  <div id="modalNuevaAula" class="modal-backdrop">
    <!-- ... contenido del modal ... -->
  </div>

  <div id="modalConfirmacionCerrarSesion" class="modal-confirmacion">
    <!-- ... contenido del modal ... -->
  </div>

  <script>
    // FUNCIÓN PRINCIPAL CORREGIDA
    function mostrarFormularioRepresentante() {
      console.log('=== FUNCIÓN mostrarFormularioRepresentante EJECUTADA ===');
      
      // Obtener elementos
      const form = document.getElementById('form_representante');
      const boton = document.getElementById('btnAgregarRepresentante');
      
      // Verificar que los elementos existen
      if (!form) {
        console.error('ERROR: No se encontró el elemento #form_representante');
        alert('Error: No se puede encontrar el formulario de representante');
        return;
      }
      
      if (!boton) {
        console.error('ERROR: No se encontró el elemento #btnAgregarRepresentante');
        return;
      }
      
      console.log('Elementos encontrados correctamente');
      console.log('Estado INICIAL del formulario:', form.style.display);
      console.log('Estado INICIAL del botón:', boton.style.display);
      
      // 1. OCULTAR EL BOTÓN
      boton.style.display = 'none';
      console.log('Botón ocultado');
      
      // 2. MOSTRAR EL FORMULARIO
      form.style.display = 'block';
      form.style.visibility = 'visible';
      form.style.opacity = '1';
      
      // 3. APLICAR ANIMACIÓN
      form.style.animation = 'fadeIn 0.5s ease';
      
      console.log('Estado FINAL del formulario:', form.style.display);
      console.log('Formulario mostrado exitosamente');
      
      // 4. HACER SCROLL HASTA EL FORMULARIO
      setTimeout(function() {
        form.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }, 100);
      
      // 5. PONER FOCO EN EL PRIMER CAMPO
      setTimeout(function() {
        const primerCampo = form.querySelector('input, textarea, select');
        if (primerCampo) {
          primerCampo.focus();
        }
      }, 200);
    }

    // INICIALIZACIÓN - OCULTAR FORMULARIO AL CARGAR LA PÁGINA
    document.addEventListener('DOMContentLoaded', function() {
      console.log('=== PÁGINA CARGADA - INICIALIZANDO ===');
      
      const form = document.getElementById('form_representante');
      const boton = document.getElementById('btnAgregarRepresentante');
      
      if (form && boton) {
        // Ocultar formulario
        form.style.display = 'none';
        
        // Asegurar que el botón sea visible
        boton.style.display = 'inline-flex';
        
        console.log('Inicialización completada:');
        console.log('- Formulario oculto');
        console.log('- Botón visible');
      } else {
        console.error('Error en inicialización: elementos no encontrados');
      }
      
      // Inicializar otros eventos
      cargarAulas();
    });

    // FUNCIONES RESTANTES (se mantienen igual)
    function mostrarConfirmacionCerrarSesion() {
      document.getElementById('modalConfirmacionCerrarSesion').style.display = 'block';
    }

    function cerrarConfirmacionCerrarSesion() {
      document.getElementById('modalConfirmacionCerrarSesion').style.display = 'none';
    }

    function confirmarCerrarSesion() {
      window.location.href = 'login.php';
    }

    function toggleCampo(tipo) {
      const campo = document.getElementById(`campo_${tipo}`);
      const check = document.getElementById(`${tipo}_check`);
      campo.style.display = check.checked ? 'block' : 'none';
      
      if (check.checked) {
        campo.style.animation = 'fadeIn 0.3s ease';
      }
    }

    function toggleDetalleProcedencia() {
      const select = document.getElementById('lugar_procedencia');
      const container = document.getElementById('detalle_procedencia_container');
      container.style.display = select.value === 'Otro' ? 'block' : 'none';
      
      if (select.value === 'Otro') {
        container.style.animation = 'fadeIn 0.3s ease';
      }
    }

    function calcularEdad() {
      const fechaNacimiento = new Date(document.getElementById('fecha_nacimiento').value);
      const hoy = new Date();
      let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
      const mes = hoy.getMonth() - fechaNacimiento.getMonth();
      
      if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
        edad--;
      }
      
      document.getElementById('edad').value = edad >= 0 ? edad : '';
    }

    function generarCedulaEscolar() {
      const numeroHijo = document.getElementById('numero_hijo').value;
      const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
      const cedulaRepresentante = document.getElementById('cedula_representante').value;
      
      if (numeroHijo && fechaNacimiento && cedulaRepresentante) {
        const añoNacimiento = fechaNacimiento.substring(2, 4);
        const cedulaLimpia = cedulaRepresentante.replace(/\D/g, '');
        const cedulaEscolar = numeroHijo + añoNacimiento + cedulaLimpia;
        
        document.getElementById('cedula_escolar').value = cedulaEscolar;
        document.getElementById('cedula_escolar_hidden').value = cedulaEscolar;
      } else {
        document.getElementById('cedula_escolar').value = '';
        document.getElementById('cedula_escolar_hidden').value = '';
      }
    }

    function cargarAulas() {
      const turno = document.getElementById('turno').value;
      const nivel = document.getElementById('nivel').value;
      const aulaSelect = document.getElementById('aula_id');
      
      if (!turno || !nivel) {
        aulaSelect.innerHTML = '<option value="">Primero seleccione turno y nivel</option>';
        return;
      }
      
      const aulas = {
        'Mañana': {
          '1°': ['A - GRUPO A (25 alumnos)', 'B - GRUPO A (25 alumnos)'],
          '2°': ['A - GRUPO A (25 alumnos)', 'B - GRUPO A (25 alumnos)'],
          '3°': ['A - GRUPO A (25 alumnos)', 'B - GRUPO A (25 alumnos)']
        },
        'Tarde': {
          '1°': ['A - GRUPO B (25 alumnos)', 'B - GRUPO B (25 alumnos)'],
          '2°': ['A - GRUPO B (25 alumnos)', 'B - GRUPO B (25 alumnos)'],
          '3°': ['A - GRUPO B (25 alumnos)', 'B - GRUPO B (25 alumnos)']
        }
      };
      
      let options = '<option value="">Seleccionar aula</option>';
      
      if (aulas[turno] && aulas[turno][nivel]) {
        aulas[turno][nivel].forEach((aula, index) => {
          const letraSeccion = String.fromCharCode(65 + index);
          const valor = `${turno}_${nivel}_${letraSeccion}`;
          options += `<option value="${valor}">${aula}</option>`;
        });
      }
      
      aulaSelect.innerHTML = options;
    }

    function mostrarModalNuevaAula() {
      document.getElementById('modalNuevaAula').style.display = 'block';
    }

    function cerrarModalNuevaAula() {
      document.getElementById('modalNuevaAula').style.display = 'none';
    }

    function crearNuevaAula() {
      const turno = document.getElementById('nuevo_turno').value;
      const nivel = document.getElementById('nuevo_nivel').value;
      const seccion = document.getElementById('nueva_seccion').value;
      const capacidad = document.getElementById('nueva_capacidad').value;
      
      const aulaSelect = document.getElementById('aula_id');
      const valorExistente = `${turno}_${nivel}_${seccion}`;
      
      for (let i = 0; i < aulaSelect.options.length; i++) {
        if (aulaSelect.options[i].value === valorExistente) {
          alert('⚠️ Esta sección ya existe para el turno y nivel seleccionados.');
          return;
        }
      }
      
      const grupo = turno === 'Mañana' ? 'A' : 'B';
      const texto = `${seccion} - GRUPO ${grupo} (${capacidad} alumnos)`;
      const nuevaOpcion = new Option(texto, valorExistente);
      aulaSelect.add(nuevaOpcion);
      aulaSelect.value = valorExistente;
      
      alert(`✅ Nueva sección creada:\nTurno: ${turno}\nNivel: ${nivel}\nSección: ${seccion}\nCapacidad: ${capacidad} alumnos`);
      cerrarModalNuevaAula();
    }

    // Cerrar modales al hacer clic fuera
    document.getElementById('modalNuevaAula').addEventListener('click', function(e) {
      if (e.target === this) {
        cerrarModalNuevaAula();
      }
    });

    document.getElementById('modalConfirmacionCerrarSesion').addEventListener('click', function(e) {
      if (e.target === this) {
        cerrarConfirmacionCerrarSesion();
      }
    });

    // Restaurar botón si se limpia el formulario
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
      setTimeout(function() {
        const form = document.getElementById('form_representante');
        const boton = document.getElementById('btnAgregarRepresentante');
        
        if (form && boton) {
          form.style.display = 'none';
          boton.style.display = 'inline-flex';
          console.log('Formulario restaurado después de limpiar');
        }
      }, 100);
    });
  </script>
</body>
</html>