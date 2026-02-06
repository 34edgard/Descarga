<?php
include 'includes/conexion.php';

$nivel = isset($_GET['nivel']) ? mysqli_real_escape_string($conexion, $_GET['nivel']) : '';
$turno = isset($_GET['turno']) ? mysqli_real_escape_string($conexion, $_GET['turno']) : '';

// Construimos la consulta dinámica
$sql = "SELECT id, letra, nivel, turno FROM secciones WHERE 1=1";

if (!empty($nivel)) {
    $sql .= " AND nivel = '$nivel'";
}
if (!empty($turno)) {
    $sql .= " AND turno = '$turno'";
}

$sql .= " ORDER BY nivel, turno, letra";

$query = mysqli_query($conexion, $sql);
$json = [];

while($row = mysqli_fetch_assoc($query)){
    $json[] = [
        'id' => $row['id'],
        'texto' => "Sección " . $row['letra'] . " (" . $row['nivel'] . " - " . $row['turno'] . ")"
    ];
}

echo json_encode($json);
?>