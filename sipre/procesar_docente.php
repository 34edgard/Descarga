<?php
require_once 'includes/auth.php';
require_once 'includes/conexion.php';

$id = $_POST['id'] ?? '';
$nombre = trim($_POST['nombre']);
$cedula = trim($_POST['cedula']);
$telefono = trim($_POST['telefono']);
$correo = trim($_POST['correo']);
$nivel = $_POST['nivel'];
$foto = '';

if(isset($_FILES['foto']) && $_FILES['foto']['error']==0){
    $nombre_foto = time().'_'.basename($_FILES['foto']['name']);
    $destino = 'uploads/'.$nombre_foto;
    if(move_uploaded_file($_FILES['foto']['tmp_name'],$destino)){
        $foto = $destino;
    }
}

if($id){ // Editar
    if($foto){
        $sql = "UPDATE docentes SET nombre=?, cedula=?, telefono=?, correo=?, nivel=?, foto=? WHERE id=?";
        $stmt = mysqli_prepare($conexion,$sql);
        mysqli_stmt_bind_param($stmt,"ssssssi",$nombre,$cedula,$telefono,$correo,$nivel,$foto,$id);
    } else {
        $sql = "UPDATE docentes SET nombre=?, cedula=?, telefono=?, correo=?, nivel=? WHERE id=?";
        $stmt = mysqli_prepare($conexion,$sql);
        mysqli_stmt_bind_param($stmt,"sssssi",$nombre,$cedula,$telefono,$correo,$nivel,$id);
    }
} else { // Nuevo
    $sql = "INSERT INTO docentes(nombre,cedula,telefono,correo,nivel,foto) VALUES(?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conexion,$sql);
    mysqli_stmt_bind_param($stmt,"ssssss",$nombre,$cedula,$telefono,$correo,$nivel,$foto);
}

if(mysqli_stmt_execute($stmt)){
    header("Location: gestion_docentes.php");
} else {
    echo "❌ Error: ".mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
