<?php
session_start();
include 'includes/conexion.php';

$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

if ($usuario === '' || $password === '') {
    header("Location: login.php?error=1");
    exit;
}

$sql = "SELECT id, usuario, password, rol FROM usuarios WHERE usuario = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($fila = mysqli_fetch_assoc($result)) {

    if (password_verify($password, $fila['password'])) {

        // Guardar sesión
        $_SESSION['usuario_id'] = $fila['id'];
        $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['rol'] = $fila['rol'];
        $_SESSION['ultimo_acceso'] = time();

        header("Location: dashboard.php");
        exit;
    }
}

header("Location: login.php?error=1");
exit;
