<?php
session_start();
include 'includes/conexion.php';

// 1. SEGURIDAD: Solo Administradores pueden entrar aquí
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Administrador') {
    echo "<script>alert('⛔ ACCESO DENEGADO: Solo el Administrador puede gestionar usuarios.'); window.location.href='panel_alumnos.php';</script>";
    exit;
}

$nombre_usuario = $_SESSION['nombre'] ?? 'Administrador';

// 2. LÓGICA: ELIMINAR USUARIO
if (isset($_GET['eliminar_id'])) {
    $id_borrar = intval($_GET['eliminar_id']);
    
    // Evitar que el admin se borre a sí mismo
    if ($id_borrar == $_SESSION['usuario_id']) {
        $error = "❌ No puedes eliminar tu propia cuenta mientras estás logueado.";
    } else {
        $sql_del = "DELETE FROM usuarios WHERE id = $id_borrar";
        if (mysqli_query($conexion, $sql_del)) {
            $exito = "✅ Usuario eliminado correctamente.";
        } else {
            $error = "❌ Error al eliminar: " . mysqli_error($conexion);
        }
    }
}

// 3. LÓGICA: CREAR USUARIO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_usuario'])) {
    $nombre = mysqli_real_escape_string($conexion, strtoupper($_POST['nombre']));
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
    $password_plana = $_POST['contrasena'];
    $rol = mysqli_real_escape_string($conexion, $_POST['rol']); // Importante sanitizar

    // Validar correo duplicado
    $check = mysqli_query($conexion, "SELECT id FROM usuarios WHERE correo = '$correo'");
    if (mysqli_num_rows($check) > 0) {
        $error = "❌ El correo '$correo' ya está registrado.";
    } else {
        // Encriptar contraseña
        $password_hash = password_hash($password_plana, PASSWORD_DEFAULT);

        // Manejo de FOTO
        $nombre_foto = "img/logo.png"; 
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $nombre_foto = "uploads/user_" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], $nombre_foto);
        }

        $sql_insert = "INSERT INTO usuarios (nombre, correo, contraseña, cedula, rol, foto) 
                       VALUES ('$nombre', '$correo', '$password_hash', '$cedula', '$rol', '$nombre_foto')";
        
        if (mysqli_query($conexion, $sql_insert)) {
            $exito = "✅ Usuario '$nombre' creado exitosamente con rol: $rol.";
        } else {
            $error = "❌ Error al crear usuario: " . mysqli_error($conexion);
        }
    }
}

// 4. CONSULTA DE USUARIOS
$sql_usuarios = "SELECT * FROM usuarios ORDER BY rol ASC, nombre ASC";
$query_usuarios = mysqli_query($conexion, $sql_usuarios);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - SIPRE</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; --success: #28a745; --danger: #e74c3c; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); margin: 0; padding: 20px; min-height: 100vh; }
        
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: fadeIn 0.5s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        h2 { color: #333; margin: 0; }
        
        .btn { padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; color: white; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; font-size: 14px; }
        .btn-add { background: var(--success); } .btn-add:hover { background: #218838; transform: translateY(-2px); }
        .btn-back { background: #6c757d; } .btn-back:hover { background: #5a6268; }
        .btn-del { background: var(--danger); padding: 6px 12px; font-size: 12px; border-radius: 5px; } .btn-del:hover { background: #c0392b; }

        .user-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .user-table th { background: #f8f9fa; color: #555; padding: 12px; text-align: left; border-bottom: 2px solid #ddd; }
        .user-table td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .user-table tr:hover { background: #f9f9f9; }
        
        .user-avatar { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary); }
        
        /* Badges de Roles */
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; color: white; }
        .bg-admin { background: #667eea; }
        .bg-directora { background: #e67e22; }
        .bg-secretaria { background: #1abc9c; }

        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: white; width: 400px; padding: 30px; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.3); animation: slideDown 0.3s; position: relative; }
        @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; font-size: 13px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .close-modal { position: absolute; top: 15px; right: 15px; font-size: 20px; cursor: pointer; color: #999; }
        .close-modal:hover { color: #333; }

        @media (max-width: 768px) {
            .header { flex-direction: column; gap: 10px; text-align: center; }
            .user-table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>
            <h2><i class="fas fa-users-cog"></i> Gestión de Usuarios</h2>
            <p style="color:#777; margin:5px 0 0 0; font-size:13px;">Panel de Administración de Acceso</p>
        </div>
        <div style="display:flex; gap:10px;">
            <button onclick="abrirModal()" class="btn btn-add"><i class="fas fa-user-plus"></i> Nuevo Usuario</button>
            <a href="panel_alumnos.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>
    </div>

    <?php if(isset($exito)): ?>
        <script>Swal.fire('¡Excelente!', '<?php echo $exito; ?>', 'success');</script>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <script>Swal.fire('Error', '<?php echo $error; ?>', 'error');</script>
    <?php endif; ?>

    <table class="user-table">
        <thead>
            <tr>
                <th width="60">Foto</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th style="text-align:right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($query_usuarios)): 
                // Color del badge y texto visual
                $badge_class = 'bg-secretaria';
                $rol_visual = $row['rol']; // Lo que se muestra en pantalla

                // AJUSTAR VISUALIZACIÓN SEGÚN LO GUARDADO EN BD
                if($row['rol'] == 'Administrador') {
                    $badge_class = 'bg-admin';
                }
                elseif($row['rol'] == 'Director') { // En BD dice "Director"
                    $badge_class = 'bg-directora';
                    $rol_visual = 'Directora / Director'; // Mostramos esto visualmente
                }
                elseif($row['rol'] == 'Secretario') { // En BD dice "Secretario"
                    $badge_class = 'bg-secretaria';
                    $rol_visual = 'Secretaria / Secretario';
                }
                
                $foto = !empty($row['foto']) ? $row['foto'] : 'img/logo.png';
            ?>
            <tr>
                <td><img src="<?php echo $foto; ?>" class="user-avatar" onerror="this.src='img/logo.png'"></td>
                <td>
                    <strong><?php echo $row['nombre']; ?></strong><br>
                    <small style="color:#888;">C.I: <?php echo $row['cedula']; ?></small>
                </td>
                <td><?php echo $row['correo']; ?></td>
                <td><span class="badge <?php echo $badge_class; ?>"><?php echo $rol_visual; ?></span></td>
                <td style="text-align:right;">
                    <?php if($row['id'] != $_SESSION['usuario_id']): ?>
                        <a href="#" onclick="confirmarBorrar(<?php echo $row['id']; ?>, '<?php echo $row['nombre']; ?>')" class="btn btn-del">
                            <i class="fas fa-trash"></i> Eliminar
                        </a>
                    <?php else: ?>
                        <span style="color:#aaa; font-size:12px;">(Tú)</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div id="modalUsuario" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="cerrarModal()">&times;</span>
        <h3 style="text-align:center; color:var(--primary); margin-top:0;"><i class="fas fa-user-shield"></i> Nuevo Usuario</h3>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nombre Completo:</label>
                <input type="text" name="nombre" required placeholder="Ej: María Pérez" autocomplete="off">
            </div>
            
            <div class="form-group">
                <label>Cédula:</label>
                <input type="text" name="cedula" required placeholder="Ej: 12345678">
            </div>

            <div class="form-group">
                <label>Correo Institucional:</label>
                <input type="email" name="correo" required placeholder="usuario@sistema.com" autocomplete="off">
            </div>

            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="contrasena" required placeholder="******">
            </div>

            <div class="form-group">
                <label>Rol de Acceso:</label>
                <select name="rol" required>
                    <option value="">Seleccione...</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Director">Directora / Director</option>
                    <option value="Secretario">Secretaria / Secretario</option>
                </select>
            </div>

            <div class="form-group">
                <label>Foto de Perfil (Opcional):</label>
                <input type="file" name="foto" accept="image/*">
            </div>

            <button type="submit" name="crear_usuario" class="btn btn-add" style="width:100%; justify-content:center;">
                <i class="fas fa-save"></i> Guardar Usuario
            </button>
        </form>
    </div>
</div>

<script>
    function abrirModal() {
        document.getElementById('modalUsuario').style.display = 'flex';
    }

    function cerrarModal() {
        document.getElementById('modalUsuario').style.display = 'none';
    }

    window.onclick = function(event) {
        var modal = document.getElementById('modalUsuario');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function confirmarBorrar(id, nombre) {
        Swal.fire({
            title: '¿Eliminar Usuario?',
            text: "Estás a punto de eliminar a " + nombre + ". Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "modulo_usuarios.php?eliminar_id=" + id;
            }
        })
    }
</script>

</body>
</html>