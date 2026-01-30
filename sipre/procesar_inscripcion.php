<?php
// ACTIVAR MANEJO DE ERRORES
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/conexion.php';

// VERIFICAR CONEXIÓN
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// VERIFICAR MÉTODO POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Método no permitido");
}

try {
    // VALIDAR DATOS OBLIGATORIOS DEL ALUMNO
    $campos_obligatorios = ['nombre_alumno', 'cedula_escolar', 'fecha_nacimiento', 'sexo', 'nivel', 'docente_id'];
    foreach ($campos_obligatorios as $campo) {
        if (empty($_POST[$campo])) {
            throw new Exception("Falta el campo obligatorio: $campo");
        }
    }

    // DATOS DEL ALUMNO (CON SANEAMIENTO)
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre_alumno']);
    $cedula = mysqli_real_escape_string($conexion, $_POST['cedula_escolar']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
    $sexo = mysqli_real_escape_string($conexion, $_POST['sexo']);
    $nivel = mysqli_real_escape_string($conexion, $_POST['nivel']);
    $docente_id = intval($_POST['docente_id']);
    
    // CAMPOS ADICIONALES OBLIGATORIOS SEGÚN TU BD
    $lugar_procedencia = mysqli_real_escape_string($conexion, $_POST['lugar_procedencia'] ?? 'Hogar');
    $direccion_nino = mysqli_real_escape_string($conexion, $_POST['direccion_nino'] ?? 'Dirección no especificada');
    $nacionalidad = mysqli_real_escape_string($conexion, $_POST['nacionalidad'] ?? 'Venezolana');
    $municipio = mysqli_real_escape_string($conexion, $_POST['municipio'] ?? 'No especificado');
    $talla_camisa = mysqli_real_escape_string($conexion, $_POST['talla_camisa'] ?? 'M');
    $talla_pantalon = mysqli_real_escape_string($conexion, $_POST['talla_pantalon'] ?? 'M');
    $talla_zapatos = mysqli_real_escape_string($conexion, $_POST['talla_zapatos'] ?? '28');
    $ano_escolar = mysqli_real_escape_string($conexion, $_POST['ano_escolar'] ?? '2024-2025');

    // CONDICIONES MÉDICAS
    $condiciones = "";
    if (isset($_POST['condicion_medica']) && is_array($_POST['condicion_medica'])) {
        $condiciones = mysqli_real_escape_string($conexion, implode(", ", $_POST['condicion_medica']));
    }
    
    $detalle_alergia = isset($_POST['detalle_alergia']) ? mysqli_real_escape_string($conexion, trim($_POST['detalle_alergia'])) : '';
    $detalle_discapacidad = isset($_POST['detalle_discapacidad']) ? mysqli_real_escape_string($conexion, trim($_POST['detalle_discapacidad'])) : '';

    if ($detalle_alergia) {
        $condiciones .= " (Alergia: $detalle_alergia)";
    }
    if ($detalle_discapacidad) {
        $condiciones .= " (Discapacidad: $detalle_discapacidad)";
    }

    // MANEJO DE FOTO
    $foto_ruta = "img/logo.png"; // Valor por defecto
    if (isset($_FILES['foto_alumno']) && $_FILES['foto_alumno']['error'] == 0) {
        $foto_nombre = basename($_FILES['foto_alumno']['name']);
        $foto_temp = $_FILES['foto_alumno']['tmp_name'];
        $foto_ruta = "uploads/" . uniqid() . "_" . $foto_nombre;
        
        // CREAR CARPETA SI NO EXISTE
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        if (!move_uploaded_file($foto_temp, $foto_ruta)) {
            $foto_ruta = "img/logo.png"; // Usar logo si falla
        }
    }

    // VALIDAR DATOS DEL REPRESENTANTE
    if (empty($_POST['nombre_representante']) || empty($_POST['cedula_representante'])) {
        throw new Exception("Datos del representante incompletos");
    }

    // DATOS DEL REPRESENTANTE (CON SANEAMIENTO)
    $nombre_representante = mysqli_real_escape_string($conexion, $_POST['nombre_representante']);
    $cedula_representante = mysqli_real_escape_string($conexion, $_POST['cedula_representante']);
    $telefono_representante = mysqli_real_escape_string($conexion, $_POST['telefono_representante'] ?? '');
    $correo_representante = mysqli_real_escape_string($conexion, $_POST['correo_representante'] ?? '');

    // CAMPOS OBLIGATORIOS PARA REPRESENTANTE SEGÚN TU BD
    $condicion_rep = mysqli_real_escape_string($conexion, $_POST['condicion_representante'] ?? 'Madre Soltera');
    $direccion_rep = mysqli_real_escape_string($conexion, $_POST['direccion_representante'] ?? 'Dirección no especificada');
    $profesion_rep = mysqli_real_escape_string($conexion, $_POST['profesion_representante'] ?? 'No especificada');
    $edad_rep = intval($_POST['edad_representante'] ?? 30);
    $nacionalidad_rep = mysqli_real_escape_string($conexion, $_POST['nacionalidad_representante'] ?? 'Venezolana');
    $nivel_instruccion_rep = mysqli_real_escape_string($conexion, $_POST['nivel_instruccion_representante'] ?? 'Bachiller');
    $ocupacion_rep = mysqli_real_escape_string($conexion, $_POST['ocupacion_representante'] ?? 'No especificada');

    // INSERTAR REPRESENTANTE (CON TODOS LOS CAMPOS OBLIGATORIOS)
    $sql_representante = "INSERT INTO representantes (
        nombre, cedula, telefono, correo, condicion, direccion_representante, 
        profesion_representante, edad_representante, nacionalidad_representante, 
        nivel_instruccion_representante, ocupacion_representante
    ) VALUES (
        '$nombre_representante', '$cedula_representante', '$telefono_representante', 
        '$correo_representante', '$condicion_rep', '$direccion_rep', '$profesion_rep',
        $edad_rep, '$nacionalidad_rep', '$nivel_instruccion_rep', '$ocupacion_rep'
    )";
    
    if (!mysqli_query($conexion, $sql_representante)) {
        throw new Exception("Error al insertar representante: " . mysqli_error($conexion));
    }
    
    $representante_id = mysqli_insert_id($conexion);

    // INSERTAR ALUMNO (CON TODOS LOS CAMPOS OBLIGATORIOS)
    $sql_alumno = "INSERT INTO alumnos (
        nombre, cedula_escolar, fecha_nacimiento, sexo, nivel, condicion_medica, 
        foto, representante_id, docente_id, lugar_procedencia, direccion_nino, 
        nacionalidad, municipio, estado, talla_camisa, talla_pantalon, 
        talla_zapatos, ano_escolar, doc_partida_nacimiento, doc_copia_cedula_madre,
        doc_copia_cedula_padre, doc_fotos_carnet, doc_certificado_vacunas
    ) VALUES (
        '$nombre', '$cedula', '$fecha_nacimiento', '$sexo', '$nivel', '$condiciones',
        '$foto_ruta', $representante_id, $docente_id, '$lugar_procedencia', 
        '$direccion_nino', '$nacionalidad', '$municipio', 'Monagas', 
        '$talla_camisa', '$talla_pantalon', '$talla_zapatos', '$ano_escolar',
        1, 1, 1, 1, 1
    )";

    if (mysqli_query($conexion, $sql_alumno)) {
        // ÉXITO - MOSTRAR RESPUESTA
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>Inscripción exitosa</title>
            <link rel='stylesheet' href='css/estilos.css'>
            <style>
                .resumen {
                    background-color: #fff;
                    padding: 25px;
                    max-width: 600px;
                    margin: auto;
                    margin-top: 40px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    text-align: center;
                }
                .resumen img {
                    max-width: 150px;
                    border-radius: 8px;
                    margin-bottom: 15px;
                }
                .resumen h3 {
                    color: #4CAF50;
                }
                .resumen p {
                    margin: 5px 0;
                }
            </style>
        </head>
        <body>
            <div class='resumen'>
                <h3>✅ Inscripción guardada correctamente</h3>
                <img src='$foto_ruta' alt='Foto del alumno'>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Cédula escolar:</strong> $cedula</p>
                <p><strong>Fecha de nacimiento:</strong> $fecha_nacimiento</p>
                <p><strong>Sexo:</strong> $sexo</p>
                <p><strong>Nivel:</strong> $nivel</p>
                <p><strong>Condiciones médicas:</strong> " . ($condiciones ?: 'Ninguna') . "</p>
                <p><strong>Representante:</strong> $nombre_representante</p>
                <p><strong>Teléfono:</strong> $telefono_representante</p>
                <a href='formulario_inscripcion.php' style='display: inline-block; margin-top: 15px; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Registrar otro alumno</a>
            </div>
        </body>
        </html>";
    } else {
        throw new Exception("Error al insertar alumno: " . mysqli_error($conexion));
    }

} catch (Exception $e) {
    echo "<div style='padding: 20px; background: #ffebee; color: #c62828; border-radius: 8px; max-width: 600px; margin: 40px auto;'>
            <h3>❌ Error en el registro</h3>
            <p><strong>Motivo:</strong> " . $e->getMessage() . "</p>
            <a href='form_inscripcion.php' style='color: #1976d2;'>Volver al formulario</a>
          </div>";
}

mysqli_close($conexion);
?>