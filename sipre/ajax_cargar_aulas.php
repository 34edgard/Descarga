<?php
include 'includes/conexion.php';

if(isset($_GET['nivel'], $_GET['turno'])){
    $nivel = $_GET['nivel'];
    $turno = $_GET['turno'];

    $stmt = $conexion->prepare("SELECT id, nombre_grupo FROM aulas WHERE nivel=? AND turno=? ORDER BY nombre_grupo ASC");
    $stmt->bind_param("ss", $nivel, $turno);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $aulas = [];
    while($fila = $resultado->fetch_assoc()){
        $aulas[] = $fila;
    }
    echo json_encode($aulas);
}
?>
