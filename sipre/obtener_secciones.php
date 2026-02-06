<?php
include '../includes/conexion.php';
$aula_id=$_GET['aula_id']??0;
$stmt=$conexion->prepare("SELECT id,letra FROM secciones WHERE aula_id=? ORDER BY letra ASC");
$stmt->bind_param("i",$aula_id);
$stmt->execute();
$result=$stmt->get_result();
echo json_encode($result->fetch_all(MYSQLI_ASSOC));
?>
