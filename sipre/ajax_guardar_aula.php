<?php
// 1. DESACTIVAR ERRORES VISUALES (Para que no rompan el JSON)
error_reporting(0);
ini_set('display_errors', 0);

// 2. INICIAR BUFFER DE SALIDA (Atrapa cualquier texto "basura")
ob_start();

include 'includes/conexion.php';

// Definir respuesta por defecto
$response = ['success' => false, 'message' => 'Error inicial'];

try {
    if(isset($_POST['nombre'], $_POST['nivel'], $_POST['turno'])){
        
        $nombre = strtoupper(trim($_POST['nombre']));
        $nivel = $_POST['nivel'];
        $turno = $_POST['turno'];

        // Verificar conexión
        if (!$conexion) {
            throw new Exception("Error de conexión a la BD");
        }

        // Verificar duplicados
        $sql_check = "SELECT id FROM aulas WHERE nombre_grupo = '$nombre' AND nivel = '$nivel' AND turno = '$turno'";
        $check = mysqli_query($conexion, $sql_check);

        if($check && mysqli_num_rows($check) > 0) {
            $response = ['success' => false, 'message' => 'Esta aula ya existe.'];
        } else {
            // Insertar
            $sql = "INSERT INTO aulas (nombre_grupo, nivel, turno) VALUES ('$nombre', '$nivel', '$turno')";
            
            if(mysqli_query($conexion, $sql)){
                $response = [
                    'success' => true, 
                    'id' => mysqli_insert_id($conexion),
                    'message' => 'Aula guardada'
                ];
            } else {
                throw new Exception("Error SQL: " . mysqli_error($conexion));
            }
        }
    } else {
        $response = ['success' => false, 'message' => 'Faltan datos en el POST'];
    }

} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
}

// 3. LIMPIAR BUFFER (Borra cualquier HTML/Warning previo)
ob_clean(); 

// 4. AHORA SÍ, ENVIAR SOLO EL JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit;
?>