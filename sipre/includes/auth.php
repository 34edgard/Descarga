<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función para proteger páginas según rol
function proteger_pagina($roles_permitidos = []) {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../login.php");
        exit;
    }

    if (!empty($roles_permitidos) && !in_array($_SESSION['rol'], $roles_permitidos)) {
        echo "<h3>⛔ Acceso restringido</h3>";
        exit;
    }
}
