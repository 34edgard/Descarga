<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/conexion.php';

function login_exitoso($fila) {
    $_SESSION['usuario_id'] = $fila['id'];
    $_SESSION['nombre'] = $fila['nombre'];
    $_SESSION['rol'] = $fila['rol'];
    $_SESSION['foto'] = $fila['foto'] ?? '';
    $_SESSION['login_time'] = time();
    
    header("Location: panel_alumnos.php");
    exit;
}

function actualizar_password($usuario_id, $nuevo_hash, $conexion) {
    $sql_update = "UPDATE usuarios SET contraseña = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($conexion, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "si", $nuevo_hash, $usuario_id);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['correo']) || !isset($_POST['contraseña'])) {
    $_SESSION['login_error'] = "⛔ Acceso no autorizado.";
    header("Location: login.php");
    exit;
}

$correo = trim($_POST['correo']);
$contraseña = $_POST['contraseña'];

if (empty($correo) || empty($contraseña)) {
    $_SESSION['login_error'] = "❌ Debe ingresar correo y contraseña.";
    header("Location: login.php");
    exit;
}

$sql = "SELECT id, nombre, correo, contraseña, rol, foto FROM usuarios WHERE correo = ?";
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    $_SESSION['login_error'] = "❌ Error en la consulta.";
    header("Location: login.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if ($fila = mysqli_fetch_assoc($resultado)) {
    if (password_verify($contraseña, $fila['contraseña'])) {
        login_exitoso($fila);
    } else if (empty($fila['contraseña'])) {
        $nuevo_hash = password_hash($contraseña, PASSWORD_DEFAULT);
        actualizar_password($fila['id'], $nuevo_hash, $conexion);
        login_exitoso($fila);
    } else if ($contraseña === $fila['contraseña']) {
        $nuevo_hash = password_hash($contraseña, PASSWORD_DEFAULT);
        actualizar_password($fila['id'], $nuevo_hash, $conexion);
        login_exitoso($fila);
    } else {
        $_SESSION['login_error'] = "❌ Contraseña incorrecta.";
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = "❌ Usuario no encontrado.";
    header("Location: login.php");
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
