<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sistema de Impresión de Certificados</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }
        
        .institution-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        
        .logo-container {
            flex-shrink: 0;
        }
        
        .institution-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: block;
        }
        
        .institution-info {
            text-align: left;
        }
        
        .institution-name {
            color: #2c3e50;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .system-title {
            color: #3498db;
            font-size: 16px;
            font-weight: 600;
        }
        
        .top-navigation {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .top-nav-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        
        .top-nav-btn:hover {
            background-color: #5a6268;
        }
        
        .update-btn {
            background-color: #28a745;
        }
        
        .update-btn:hover {
            background-color: #218838;
        }
        
        .logout-btn {
            background-color: #dc3545;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
        }
        
        h2 {
            color: #3498db;
            font-size: 20px;
            margin: 20px 0 15px;
        }
        
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .student-table th {
            background-color: #3498db;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .student-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .student-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .student-table tr:hover {
            background-color: #f1f8ff;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        
        .promoted {
            background-color: #d4edda;
            color: #155724;
        }
        
        .level {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .action-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.3s;
        }
        
        .action-btn:hover {
            background-color: #218838;
        }
        
        .print-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .print-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
            width: auto;
            margin: 0 auto;
        }
        
        .print-btn:hover {
            background-color: #2980b9;
        }
        
        .no-students {
            text-align: center;
            padding: 20px;
            background-color: #fff3cd;
            border-radius: 5px;
            color: #856404;
            margin: 20px 0;
        }
        
        .has-students {
            text-align: center;
            padding: 20px;
            background-color: #d4edda;
            border-radius: 5px;
            color: #155724;
            margin: 20px 0;
        }
        
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .nav-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        
        .nav-btn:hover {
            background-color: #5a6268;
        }
        
        .students-btn {
            background-color: #6c757d;
        }
        
        .stats-btn {
            background-color: #17a2b8;
        }
        
        .stats-btn:hover {
            background-color: #138496;
        }
        
        .checkbox {
            margin-right: 10px;
        }
        
        .instructions {
            background-color: #e8f4fc;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 14px;
        }
        
        .level-select {
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        
        .certificate-checkbox {
            transform: scale(1.2);
            margin-right: 10px;
        }
        
        /* Estilos para el modal del certificado */
        .certificate-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            z-index: 1000;
            overflow: auto;
        }
        
        .certificate-content {
            background-color: white;
            margin: 2% auto;
            padding: 0;
            width: 80%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .certificate-body {
            padding: 40px;
            text-align: center;
            font-family: 'Times New Roman', serif;
        }
        
        .certificate-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .certificate-subtitle {
            font-size: 18px;
            margin-bottom: 30px;
            text-transform: uppercase;
        }
        
        .certificate-text {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .student-name {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            margin: 20px 0;
            text-align: center;
        }
        
        .certificate-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
        }
        
        .signature {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            border-top: 1px solid #7f8c8d;
            width: 200px;
            margin: 40px auto 10px;
        }
        
        .modal-actions {
            padding: 15px;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 0 0 10px 10px;
        }
        
        .close-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .print-certificate-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        /* Estilos para impresión */
        @media print {
            body * {
                visibility: hidden;
            }
            .certificate-modal,
            .certificate-modal * {
                visibility: visible;
            }
            .certificate-modal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: white;
            }
            .certificate-content {
                margin: 0;
                width: 100%;
                max-width: 100%;
                box-shadow: none;
                border-radius: 0;
            }
            .modal-actions {
                display: none;
            }
        }
        
        .logo-placeholder {
            width: 80px;
            height: 80px;
            background: #667eea;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            text-align: center;
        }
        
        .ministerio-placeholder {
            width: 100%;
            height: 100%;
            background: #0059a3;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            text-align: center;
            flex-direction: column;
        }

        /* Estilos para verificar imágenes */
        .image-check {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 9999;
        }

        /* Estilos específicos para el certificado oficial */
        .official-certificate {
            text-align: center;
            line-height: 1.8;
        }
        
        .official-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .official-subtitle {
            font-size: 18px;
            margin-bottom: 30px;
            text-transform: uppercase;
        }
        
        .official-text {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 20px;
            text-align: left;
            text-align: justify;
        }
        
        .directors-container {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        
        .director-section {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 40px auto 10px;
        }

        /* Logo del ministerio en el certificado */
        .ministerio-logo-certificate {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            display: block;
            object-fit: contain;
        }

        .logo-certificate-container {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3498db;
        }

        /* ========== RESPONSIVE DESIGN ========== */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .container {
                padding: 15px;
                margin: 0;
                border-radius: 5px;
            }
            
            .institution-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
                margin-bottom: 20px;
            }
            
            .institution-info {
                text-align: center;
            }
            
            .institution-name {
                font-size: 18px;
            }
            
            .system-title {
                font-size: 14px;
            }
            
            .top-navigation {
                flex-direction: column;
                gap: 10px;
            }
            
            .top-nav-btn {
                width: 100%;
                padding: 10px;
            }
            
            /* Tabla responsive */
            .student-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                font-size: 14px;
                -webkit-overflow-scrolling: touch;
            }
            
            .student-table th,
            .student-table td {
                padding: 8px 10px;
                min-width: 120px;
            }
            
            h2 {
                font-size: 18px;
                margin: 15px 0 10px;
            }
            
            .print-btn {
                width: 100%;
                padding: 12px;
                font-size: 14px;
            }
            
            .navigation {
                flex-direction: column;
                gap: 10px;
            }
            
            .nav-btn {
                width: 100%;
                padding: 12px;
            }
            
            /* Modal responsive */
            .certificate-content {
                width: 95%;
                margin: 5% auto;
            }
            
            .certificate-body {
                padding: 20px;
            }
            
            .directors-container {
                flex-direction: column;
                gap: 30px;
            }
            
            .director-section {
                width: 100%;
            }
            
            .official-text {
                font-size: 14px;
                text-align: left;
            }
            
            .official-title {
                font-size: 20px;
            }
            
            .official-subtitle {
                font-size: 16px;
            }

            /* Mejorar experiencia táctil */
            input[type="checkbox"], 
            select, 
            button {
                min-height: 44px;
            }
            
            .certificate-checkbox {
                transform: scale(1.5);
                margin-right: 15px;
            }

            /* Modal actions en móviles */
            .certificate-modal {
                padding: 10px;
            }
            
            .modal-actions {
                padding: 10px;
            }
            
            .close-btn,
            .print-certificate-btn {
                width: 48%;
                padding: 10px;
                margin: 0 1%;
            }
        }

        @media (max-width: 480px) {
            .student-table {
                font-size: 12px;
            }
            
            .student-table th,
            .student-table td {
                padding: 6px 8px;
                min-width: 100px;
            }
            
            .level-select {
                font-size: 11px;
                padding: 3px 5px;
            }
            
            .action-btn {
                font-size: 11px;
                padding: 4px 8px;
            }
            
            .certificate-body {
                padding: 15px;
            }
            
            .official-text {
                font-size: 13px;
            }
            
            .ministerio-logo-certificate {
                width: 80px;
                height: 80px;
            }
            
            .institution-name {
                font-size: 16px;
            }
            
            .instructions {
                font-size: 12px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Verificador de imágenes -->
    <div class="image-check" id="imageCheck">
        Verificando imágenes...
    </div>

    <div class="container">
        <!-- Encabezado de la institución -->
        <div class="institution-header">
            <div class="logo-container">
                <img src="img/logo.png" alt="Logo institucional" class="institution-logo" id="logoImg">
                <div id="logoPlaceholder" class="logo-placeholder" style="display: none;">LOGO COLEGIO</div>
            </div>
            <div class="institution-info">
                <div class="institution-name">COLEGIO "JOSÉ AGUSTÍN MÉNDEZ GARCÍA"</div>
                <div class="system-title">SIPRE-URUGUAY</div>
            </div>
        </div>
        
        <!-- Navegación superior -->
        <div class="top-navigation">
            <button class="top-nav-btn update-btn" id="updateBtn">Actualizar</button>
            <button class="top-nav-btn" id="mainMenuBtn">Menú principal</button>
            <button class="top-nav-btn logout-btn" id="logoutBtn">Cerrar sesión</button>
        </div>
        
        <h2>Lista de Alumnos</h2>
        
        <table class="student-table">
            <thead>
                <tr>
                    <th>Seleccionar</th>
                    <th>Nombre</th>
                    <th>ID</th>
                    <th>Nivel</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" class="certificate-checkbox" 
                         data-nombre="JAVIERLIS VALENTINA ALHUACA"
                         data-cedula="11612519409"
                         data-nacimiento="16 DE NOVIEMBRE DEL 2016"
                         data-municipio="MATURIN"
                         data-estado="MONAGAS"
                         data-anio-escolar="2025-2026"></td>
                    <td>JAVIERLIS VALENTINA ALHUACA</td>
                    <td>11612519409</td>
                    <td>
                        <select class="level-select">
                            <option>1°</option>
                            <option>2°</option>
                            <option selected>3°</option>
                        </select>
                    </td>
                    <td>
                        <select class="level-select">
                            <option>En curso</option>
                            <option selected>Promovido</option>
                            <option>Reprobado</option>
                        </select>
                    </td>
                    <td>
                        <button class="action-btn">Aplicar Cambios</button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="certificate-checkbox"
                         data-nombre="CARLOS JOSÉ MARCANO"
                         data-cedula="12210785698"
                         data-nacimiento="15 DE MARZO DEL 2016"
                         data-municipio="MATURIN"
                         data-estado="MONAGAS"
                         data-anio-escolar="2025-2026"></td>
                    <td>CARLOS JOSÉ MARCANO</td>
                    <td>12210785698</td>
                    <td>
                        <select class="level-select">
                            <option>1°</option>
                            <option>2°</option>
                            <option selected>3°</option>
                        </select>
                    </td>
                    <td>
                        <select class="level-select">
                            <option>En curso</option>
                            <option selected>Promovido</option>
                            <option>Reprobado</option>
                        </select>
                    </td>
                    <td>
                        <button class="action-btn">Aplicar Cambios</button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="certificate-checkbox"
                         data-nombre="MARÍA GIMENA CARREA"
                         data-cedula="12017251210"
                         data-nacimiento="20 DE JULIO DEL 2016"
                         data-municipio="MATURIN"
                         data-estado="MONAGAS"
                         data-anio-escolar="2025-2026"></td>
                    <td>MARÍA GIMENA CARREA</td>
                    <td>12017251210</td>
                    <td>
                        <select class="level-select">
                            <option>1°</option>
                            <option selected>2°</option>
                            <option>3°</option>
                        </select>
                    </td>
                    <td>
                        <select class="level-select">
                            <option>En curso</option>
                            <option selected>Promovido</option>
                            <option>Reprobado</option>
                        </select>
                    </td>
                    <td>
                        <button class="action-btn">Aplicar Cambios</button>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div class="instructions">
            <strong>Nota:</strong> Para imprimir certificados, los alumnos deben tener estado "Promovido" y nivel "3°".
            Use los selectores para cambiar el nivel y estado de los alumnos, luego presione "Aplicar Cambios".
        </div>
        
        <h2>Sistema de Impresión de Certificados</h2>
        
        <p>Seleccione los alumnos promovidos del 3° nivel para generar sus certificados:</p>
        
        <div class="print-section">
            <button class="print-btn" id="printCertificatesBtn">Imprimir Certificados Seleccionados</button>
        </div>
        
        <div id="students-message" class="no-students">
            No hay alumnos promovidos del 3° nivel disponibles para imprimir certificados.
        </div>
        
        <!-- Navegación inferior -->
        <div class="navigation">
            <button class="nav-btn students-btn" id="backToStudentsBtn">← Volver a Alumnos</button>
            <button class="nav-btn stats-btn" id="viewStatsBtn">E. Ver Estadísticas</button>
        </div>
    </div>

    <!-- Modal para mostrar el certificado OFICIAL -->
    <div id="certificateModal" class="certificate-modal">
        <div class="certificate-content">
            <div class="certificate-body">
                <!-- Logo del Ministerio -->
                <div class="logo-certificate-container">
                    <img src="img/ministerioe.png" alt="Ministerio de Educación" class="ministerio-logo-certificate" id="ministerioLogo">
                    <div id="ministerioPlaceholder" class="ministerio-placeholder" style="display: none;">
                        <div>MINISTERIO</div>
                        <div>DE EDUCACIÓN</div>
                    </div>
                </div>

                <!-- CERTIFICADO OFICIAL -->
                <div class="official-certificate">
                    <div class="official-title">CERTIFICACIÓN DE ESTUDIOS</div>
                    <div class="official-subtitle">DEL NIVEL DE EDUCACIÓN INICIAL</div>

                    <div class="official-text">
                        <p>Quien suscribe, <strong>Profa. Cruz María Calzadilla</strong>, titular de la Cédula de Identidad N° <strong>10.304.844</strong> en su condición de Director(a) del P.E. <strong>José Agustín Méndez García</strong>, ubicado en el municipio <strong>Maturín</strong>, de la parroquia <strong>San Simón</strong>, adscrita al Centro de Desarrollo de la Calidad Educativa Estadal Monagas.</p>
                        
                        <p>Por la presente certifica que el niño(a) <strong><span id="certificateStudentName" style="text-transform: uppercase;"></span></strong> portador de la Cédula Escolar Nº o Pasaporte <strong><span id="certificateStudentCedula"></span></strong>, nacido (a) en el Municipio <strong><span id="certificateMunicipio"></span></strong> del Estado <strong><span id="certificateEstado"></span></strong>, en fecha <strong><span id="certificateFechaNacimiento"></span></strong>, cursó el <strong>III Grupo</strong> de la Etapa Preescolar del Nivel de Educación Inicial durante el periodo escolar <strong><span id="certificateAnioEscolar"></span></strong> y continuará estudios en el <strong>1er. Grado</strong> del Nivel de Educación Primaria, previo cumplimiento de los requisitos exigidos en la normativa legal vigente.</p>

                        <p>Certificado que se expide en <strong>Maturín</strong>, a los <strong><span id="certificateDia"></span></strong> días del mes de <strong><span id="certificateMes"></span></strong> de <strong><span id="certificateAnio"></span></strong>.</p>
                    </div>

                    <div class="directors-container">
                        <div class="director-section">
                            <p><strong>PARA VALIDEZ A NIVEL NACIONAL</strong></p>
                            <p><strong>DIRECTOR(A)</strong></p>
                            <p>Nombre y Apellido:</p>
                            <p><strong>CRUZ MARÍA CALZADILLA</strong></p>
                            <p>Número de C.I: <strong>10.304.844</strong></p>
                            <p>Firma y Sello:</p>
                            <div class="signature-line"></div>
                        </div>
                        
                        <div class="director-section">
                            <p><strong>PARA VALIDEZ A NIVEL INTERNACIONAL</strong></p>
                            <p><strong>DIRECTOR(A)</strong></p>
                            <p>Nombre y Apellido:</p>
                            <p><strong>CAROLINA ESTABA</strong></p>
                            <p>Número de C.I: <strong>13.263.844</strong></p>
                            <p>Firma y Sello:</p>
                            <div class="signature-line"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="close-btn" id="closeCertificateBtn">Cerrar</button>
                <button class="print-certificate-btn" id="printCertificateBtn">Imprimir Certificado</button>
            </div>
        </div>
    </div>

    <script>
        // Verificar estado de las imágenes
        function checkImages() {
            const logoImg = document.getElementById('logoImg');
            const ministerioLogo = document.getElementById('ministerioLogo');
            const imageCheck = document.getElementById('imageCheck');
            
            let status = 'Estado imágenes: ';
            
            if (logoImg.complete && logoImg.naturalHeight !== 0) {
                status += '✓logo ';
            } else {
                status += '✗logo ';
                document.getElementById('logoPlaceholder').style.display = 'flex';
                logoImg.style.display = 'none';
            }
            
            if (ministerioLogo.complete && ministerioLogo.naturalHeight !== 0) {
                status += '✓ministerio';
            } else {
                status += '✗ministerio';
                document.getElementById('ministerioPlaceholder').style.display = 'flex';
                ministerioLogo.style.display = 'none';
            }
            
            imageCheck.textContent = status;
        }

        // Funcionalidad para los botones de navegación
        document.getElementById('backToStudentsBtn').addEventListener('click', function() {
            window.location.href = 'panel_alumnos.php';
        });
        
        document.getElementById('viewStatsBtn').addEventListener('click', function() {
            window.location.href = 'estadisticas.php';
        });

        document.getElementById('mainMenuBtn').addEventListener('click', function() {
            window.location.href = 'panel_alumnos.php';
        });
        
        document.getElementById('printCertificatesBtn').addEventListener('click', function() {
            const studentData = getSelectedStudentData();
            if (studentData) {
                showCertificate(studentData);
            } else {
                alert('Por favor, seleccione un alumno para imprimir su certificado.');
            }
        });
        
        document.getElementById('updateBtn').addEventListener('click', function() {
            alert('Sistema actualizado correctamente');
            updateAvailableCertificates();
            checkImages();
        });
        
        document.getElementById('logoutBtn').addEventListener('click', function() {
            if (confirm('¿Está seguro de que desea cerrar sesión?')) {
                window.location.href = 'logout.php';
            }
        });
        
        // Funcionalidad del modal del certificado
        document.getElementById('closeCertificateBtn').addEventListener('click', function() {
            document.getElementById('certificateModal').style.display = 'none';
        });
        
        document.getElementById('printCertificateBtn').addEventListener('click', function() {
            window.print();
        });
        
        // Cerrar modal al hacer clic fuera del contenido
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('certificateModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Verificar imágenes cuando cargue la página
        window.addEventListener('load', function() {
            setTimeout(checkImages, 1000);
            setTimeout(checkImages, 3000);
            
            document.getElementById('logoImg').addEventListener('load', checkImages);
            document.getElementById('logoImg').addEventListener('error', checkImages);
            document.getElementById('ministerioLogo').addEventListener('load', checkImages);
            document.getElementById('ministerioLogo').addEventListener('error', checkImages);
        });
        
        // Función para mostrar el certificado con datos automáticos
        function showCertificate(studentData) {
            // Llenar los datos del estudiante
            document.getElementById('certificateStudentName').textContent = studentData.nombre;
            document.getElementById('certificateStudentCedula').textContent = studentData.cedula;
            document.getElementById('certificateMunicipio').textContent = studentData.municipio;
            document.getElementById('certificateEstado').textContent = studentData.estado;
            document.getElementById('certificateFechaNacimiento').textContent = studentData.nacimiento;
            document.getElementById('certificateAnioEscolar').textContent = studentData.anioEscolar;
            
            // Fecha actual automática
            const today = new Date();
            const day = today.getDate();
            const month = today.toLocaleDateString('es-ES', { month: 'long' }).toUpperCase();
            const year = today.getFullYear();
            
            document.getElementById('certificateDia').textContent = day;
            document.getElementById('certificateMes').textContent = month;
            document.getElementById('certificateAnio').textContent = year;
            
            // Mostrar el modal
            document.getElementById('certificateModal').style.display = 'block';
        }

        // Función para obtener datos del estudiante seleccionado
        function getSelectedStudentData() {
            const checkbox = document.querySelector('.certificate-checkbox:checked');
            if (checkbox) {
                return {
                    nombre: checkbox.getAttribute('data-nombre'),
                    cedula: checkbox.getAttribute('data-cedula'),
                    nacimiento: checkbox.getAttribute('data-nacimiento'),
                    municipio: checkbox.getAttribute('data-municipio'),
                    estado: checkbox.getAttribute('data-estado'),
                    anioEscolar: checkbox.getAttribute('data-anio-escolar')
                };
            }
            return null;
        }
        
        // Simulación de funcionalidad para aplicar cambios
        document.querySelectorAll('.action-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const name = row.cells[1].textContent;
                const level = row.cells[3].querySelector('select').value;
                const status = row.cells[4].querySelector('select').value;
                
                alert('Cambios aplicados para ' + name + ':\nNivel: ' + level + '\nEstado: ' + status);
                
                updateAvailableCertificates();
            });
        });
        
        function updateAvailableCertificates() {
            const students = document.querySelectorAll('.student-table tbody tr');
            let hasPromotedThirdLevel = false;
            
            students.forEach(student => {
                const level = student.cells[3].querySelector('select').value;
                const status = student.cells[4].querySelector('select').value;
                const checkbox = student.cells[0].querySelector('input[type="checkbox"]');
                
                if (level === '3°' && status === 'Promovido') {
                    hasPromotedThirdLevel = true;
                    checkbox.disabled = false;
                } else {
                    checkbox.checked = false;
                    checkbox.disabled = true;
                }
            });
            
            const messageElement = document.getElementById('students-message');
            if (hasPromotedThirdLevel) {
                messageElement.textContent = 'Alumnos disponibles para imprimir certificados. Seleccione los alumnos y presione "Imprimir Certificados Seleccionados".';
                messageElement.className = 'has-students';
            } else {
                messageElement.textContent = 'No hay alumnos promovidos del 3° nivel disponibles para imprimir certificados.';
                messageElement.className = 'no-students';
            }
        }
        
        // Inicializar la verificación de alumnos disponibles
        updateAvailableCertificates();
    </script>
</body>
</html>