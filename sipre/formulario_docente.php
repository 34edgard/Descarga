<?php
session_start();
include 'includes/conexion.php';

// Proteger acceso
if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['rol'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Docente</title>
  <link rel="stylesheet" href="css/estilos.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      margin: 0;
      padding: 20px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    
    .encabezado {
      text-align: center;
      margin-bottom: 25px;
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      max-width: 600px;
      width: 100%;
    }
    
    .encabezado img {
      max-width: 80px;
      margin-bottom: 10px;
    }
    
    .usuario-info {
      text-align: center;
      font-size: 14px;
      color: #666;
      margin-top: 10px;
    }
    
    .usuario-info strong {
      color: #333;
      font-size: 16px;
    }
    
    .formulario {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      max-width: 500px;
      width: 100%;
      margin: 20px auto;
    }
    
    .formulario h2 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
      font-size: 24px;
      border-bottom: 2px solid #667eea;
      padding-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }
    
    .campo-formulario {
      margin-bottom: 20px;
    }
    
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #444;
      font-size: 14px;
    }
    
    .campo-requerido::after {
      content: " *";
      color: #e74c3c;
    }
    
    input[type="text"],
    input[type="email"],
    input[type="tel"] {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e1e1e1;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }
    
    input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .boton {
      padding: 12px 25px;
      background: #667eea;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      min-width: 120px;
      margin: 5px;
      justify-content: center;
    }
    
    .boton:hover {
      background: #5a6fd8;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    
    .boton-grande {
      width: 100%;
      padding: 14px;
      font-size: 16px;
      margin: 20px 0 10px 0;
    }
    
    .boton-secundario {
      background: #6c757d;
    }
    
    .boton-secundario:hover {
      background: #5a6268;
    }
    
    .boton-peligro {
      background: #e74c3c;
    }
    
    .boton-peligro:hover {
      background: #c0392b;
    }
    
    .centrar-botones {
      text-align: center;
      margin: 25px 0 10px 0;
    }
    
    .grupo-botones {
      display: flex;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
      margin: 20px 0;
    }
    
    .info-adicional {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      margin: 20px 0;
      border-left: 4px solid #667eea;
    }
    
    .info-adicional h3 {
      margin: 0 0 10px 0;
      color: #333;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .info-adicional p {
      margin: 5px 0;
      color: #666;
      font-size: 13px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .formulario {
      animation: fadeIn 0.5s ease;
    }
  </style>
</head>
<body>
  <div class="encabezado">
    <img src="img/logo.png" alt="Logo institucional">
    <h1 style="color: #333; margin: 10px 0 5px 0;">Sistema Escolar</h1>
    <div class="usuario-info">
      <strong><?php echo $nombre_usuario; ?></strong> (<?php echo $rol_usuario; ?>)
    </div>
  </div>

  <div class="formulario">
    <h2><i class="fas fa-chalkboard-teacher"></i> Registro de Docente</h2>
    
    <div class="info-adicional">
      <h3><i class="fas fa-info-circle"></i> Información importante</h3>
      <p><i class="fas fa-circle fa-xs"></i> Complete todos los campos obligatorios (*)</p>
      <p><i class="fas fa-circle fa-xs"></i> Verifique que los datos sean correctos antes de guardar</p>
      <p><i class="fas fa-circle fa-xs"></i> El correo institucional es opcional pero recomendado</p>
    </div>
    
    <form action="procesar_docente.php" method="POST">
      <div class="campo-formulario">
        <label for="nombre_docente" class="campo-requerido">Nombre completo:</label>
        <input type="text" name="nombre_docente" id="nombre_docente" placeholder="Ingrese el nombre completo del docente" required>
      </div>

      <div class="campo-formulario">
        <label for="cedula_docente" class="campo-requerido">Cédula:</label>
        <input type="text" name="cedula_docente" id="cedula_docente" placeholder="Número de cédula de identidad" required>
      </div>

      <div class="campo-formulario">
        <label for="telefono_docente" class="campo-requerido">Teléfono:</label>
        <input type="tel" name="telefono_docente" id="telefono_docente" placeholder="Número de teléfono de contacto" required>
      </div>

      <div class="campo-formulario">
        <label for="correo_docente">Correo institucional:</label>
        <input type="email" name="correo_docente" id="correo_docente" placeholder="correo@institucion.edu">
      </div>

      <button type="submit" class="boton boton-grande">
        <i class="fas fa-save"></i> Guardar docente
      </button>
    </form>
  </div>

  <div class="grupo-botones">
    <a href="panel_alumnos.php" class="boton boton-secundario">
      <i class="fas fa-home"></i> Menú principal
    </a>
    <a href="gestion_docentes.php" class="boton">
      <i class="fas fa-users"></i> Ver docentes
    </a>
    <form action="logout.php" method="POST" style="display: inline;">
      <button type="submit" class="boton boton-peligro">
        <i class="fas fa-times"></i> Cerrar sesión
      </button>
    </form>
  </div>
</body>
</html>