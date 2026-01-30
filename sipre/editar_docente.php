<?php
session_start();
include 'includes/conexion.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener ID del docente
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: gestion_docentes.php");
    exit;
}

// Obtener datos del docente
$query = "SELECT * FROM docentes WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    header("Location: gestion_docentes.php");
    exit;
}

$docente = mysqli_fetch_assoc($resultado);

// DEBUG: Verificar que estamos recibiendo datos
$debug_info = "";
$success = false;

// Procesar actualización si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $nombre = trim($_POST['nombre']);
    $cedula = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $nivel = trim($_POST['nivel']);
    
    // Depuración
    $debug_info .= "Datos recibidos:<br>";
    $debug_info .= "Nombre: $nombre<br>";
    $debug_info .= "Cédula: $cedula<br>";
    $debug_info .= "Teléfono: $telefono<br>";
    $debug_info .= "Correo: $correo<br>";
    $debug_info .= "Nivel: $nivel<br>";
    $debug_info .= "ID: $id<br>";
    
    // Escapar datos para SQL
    $nombre = mysqli_real_escape_string($conexion, $nombre);
    $cedula = mysqli_real_escape_string($conexion, $cedula);
    $telefono = mysqli_real_escape_string($conexion, $telefono);
    $correo = mysqli_real_escape_string($conexion, $correo);
    $nivel = mysqli_real_escape_string($conexion, $nivel);
    
    // Crear la consulta SQL
    $sql = "UPDATE docentes SET 
            nombre = '$nombre',
            cedula = '$cedula',
            telefono = '$telefono',
            correo = '$correo',
            nivel = '$nivel'
            WHERE id = $id";
    
    $debug_info .= "SQL: " . htmlspecialchars($sql) . "<br>";
    
    // Ejecutar la consulta
    if (mysqli_query($conexion, $sql)) {
        $debug_info .= "Consulta ejecutada correctamente<br>";
        $success = true;
        
        // Redireccionar con mensaje de éxito
        header("Location: gestion_docentes.php?mensaje=editado&id=" . $id);
        exit;
    } else {
        $debug_info .= "Error en consulta: " . mysqli_error($conexion) . "<br>";
    }
}

// Si estamos en modo debug, mostrar información
if (isset($_GET['debug'])) {
    echo "<pre>" . $debug_info . "</pre>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Docente</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        
        .container {
            background: white;
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #667eea;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
        }
        
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        
        .mensaje {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .exito {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .debug {
            background: #f8f9fa;
            color: #333;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-family: monospace;
            font-size: 12px;
            white-space: pre-wrap;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Docente</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="mensaje error">
                Error al actualizar el docente. Por favor, intente nuevamente.
            </div>
        <?php endif; ?>
        
        <?php if (!empty($debug_info) && isset($_GET['debug'])): ?>
            <div class="debug">
                <?php echo $debug_info; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre Completo *</label>
                <input type="text" id="nombre" name="nombre" 
                       value="<?php echo htmlspecialchars($docente['nombre']); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="cedula">Cédula *</label>
                <input type="text" id="cedula" name="cedula" 
                       value="<?php echo htmlspecialchars($docente['cedula']); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" 
                       value="<?php echo htmlspecialchars($docente['telefono']); ?>">
            </div>
            
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" 
                       value="<?php echo htmlspecialchars($docente['correo']); ?>">
            </div>
            
            <div class="form-group">
                <label for="nivel">Nivel</label>
                <select id="nivel" name="nivel">
                    <option value="">Seleccionar nivel</option>
                    <option value="Preescolar" <?php echo ($docente['nivel'] == 'Preescolar') ? 'selected' : ''; ?>>Preescolar</option>
                    <option value="Primaria" <?php echo ($docente['nivel'] == 'Primaria') ? 'selected' : ''; ?>>Primaria</option>
                    <option value="Secundaria" <?php echo ($docente['nivel'] == 'Secundaria') ? 'selected' : ''; ?>>Secundaria</option>
                    <option value="Bachillerato" <?php echo ($docente['nivel'] == 'Bachillerato') ? 'selected' : ''; ?>>Bachillerato</option>
                </select>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="gestion_docentes.php" class="btn btn-secondary">Cancelar</a>
            </div>
            
            <!-- Para debugging -->
            <input type="hidden" name="id" value="<?php echo $id; ?>">
        </form>
        
        <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
            <a href="?id=<?php echo $id; ?>&debug=1" style="color: #667eea;">Modo depuración</a>
        </div>
    </div>
    
    <script>
        // Verificar si el formulario se envió correctamente
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Formulario enviado');
        });
    </script>
</body>
</html>
<?php
mysqli_free_result($resultado);
?>