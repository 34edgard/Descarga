<?php
include 'includes/conexion.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Error desconocido'];

if(isset($_POST['letra'], $_POST['aula_id'])){
    
    $letra = mysqli_real_escape_string($conexion, strtoupper($_POST['letra']));
    $aula_id = intval($_POST['aula_id']);
    
    // Estos son opcionales pero buenos para consistencia
    $nivel = isset($_POST['nivel']) ? mysqli_real_escape_string($conexion, $_POST['nivel']) : '';
    $turno = isset($_POST['turno']) ? mysqli_real_escape_string($conexion, $_POST['turno']) : '';

    // 1. Verificar si ya existe
    $sql_check = "SELECT id FROM secciones WHERE letra='$letra' AND aula_id=$aula_id";
    $check = mysqli_query($conexion, $sql_check);

    if(mysqli_num_rows($check) > 0) {
        $response = ['success' => false, 'message' => 'Esta sección ya existe en esta aula.'];
    } else {
        // 2. Insertar
        $sql = "INSERT INTO secciones (letra, aula_id, nivel, turno) VALUES ('$letra', $aula_id, '$nivel', '$turno')";
        if(mysqli_query($conexion, $sql)){
            $response = ['success' => true, 'message' => 'Guardado exitosamente'];
        } else {
            $response = ['success' => false, 'message' => 'Error SQL: ' . mysqli_error($conexion)];
        }
    }
}

echo json_encode($response);
?>