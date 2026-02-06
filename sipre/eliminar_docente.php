<?php
require_once 'includes/auth.php';
require_once 'includes/conexion.php';

$rol_usuario = $_SESSION['rol'];
if(!in_array($rol_usuario,['Administrador','Directora'])){
    die("⛔ No tienes permisos para eliminar docentes.");
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['id_docente'])){
    $id = intval($_POST['id_docente']);
    $sql = "DELETE FROM docentes WHERE id=?";
    $stmt = mysqli_prepare($conexion,$sql);
    mysqli_stmt_bind_param($stmt,"i",$id);
    if(mysqli_stmt_execute($stmt)){
        header("Location: gestion_docentes.php");
    } else {
        echo "❌ Error: ".mysqli_error($conexion);
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conexion);
?>
