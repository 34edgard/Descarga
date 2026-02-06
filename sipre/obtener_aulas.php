<?php
include '../includes/conexion.php';
$nivel = $_GET['nivel'] ?? '';
$turno = $_GET['turno'] ?? '';
$stmt=$conexion->prepare("SELECT id,nombre_grupo FROM aulas WHERE nivel=? AND turno=?");
$stmt->bind_param("ss",$nivel,$turno);
$stmt->execute();
$result=$stmt->get_result();
echo json_encode($result->fetch_all(MYSQLI_ASSOC));
?>
