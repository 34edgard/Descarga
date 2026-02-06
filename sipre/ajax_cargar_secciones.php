<?php
include 'includes/conexion.php';

$aula_id = $_GET['aula_id'] ?? 0;

if($aula_id){
    $stmt = $conexion->prepare("SELECT id, letra FROM secciones WHERE aula_id=?");
    $stmt->bind_param('i', $aula_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $secciones = [];
    while($row = $result->fetch_assoc()){
        $secciones[] = $row;
    }
    echo json_encode($secciones);
} else {
    echo json_encode([]);
}
?>
