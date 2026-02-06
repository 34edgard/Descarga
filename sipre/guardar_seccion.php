<?php
include '../includes/conexion.php';
$letra=$_POST['letra']??'';
$aula_id=$_POST['aula_id']??0;
$stmt=$conexion->prepare("INSERT INTO secciones(aula_id,letra) VALUES(?,?)");
$stmt->bind_param("is",$aula_id,$letra);
if($stmt->execute()){ echo "Sección guardada"; } else { echo "Error: ".$conexion->error; }
?>
