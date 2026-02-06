<?php
include '../includes/conexion.php';
$nombre=$_POST['nombre_grupo']??'';
$nivel=$_POST['nivel']??'';
$turno=$_POST['turno']??'';
$stmt=$conexion->prepare("INSERT INTO aulas(nombre_grupo,nivel,turno) VALUES(?,?,?)");
$stmt->bind_param("sss",$nombre,$nivel,$turno);
if($stmt->execute()){ echo "Aula guardada"; } else { echo "Error: ".$conexion->error; }
?>
