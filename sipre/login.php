<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Acceso al Sistema "José Agustín Méndez García"</title>
  <!-- 🚀 META TAG RESPONSIVE Y ANTI-ZOOM -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="css/estilos.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent; /* 🚀 Elimina el parpadeo en móviles */
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow: hidden;
      touch-action: manipulation; /* 🚀 Previene zoom automático */
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff" fill-opacity="0.05" points="0,1000 1000,0 1000,1000"/></svg>');
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); } /* 🚀 Reducido para menos movimiento */
    }

    .login-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
      max-width: 450px;
      width: 100%;
      position: relative;
      z-index: 1;
      border: 1px solid rgba(255, 255, 255, 0.3);
      /* 🚀 ELIMINADO: transform y transition que causaban titileo */
    }

    .logo-section {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo-section img {
      max-width: 100px;
      margin-bottom: 15px;
      filter: drop-shadow(0 5px 15px rgba(102, 126, 234, 0.3));
      /* 🚀 ELIMINADO: transition que causaba movimiento */
    }

    .logo-section h1 {
      color: #333;
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 5px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .logo-section p {
      color: #666;
      font-size: 14px;
      font-weight: 500;
    }

    .login-form {
      margin-top: 30px;
    }

    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #667eea;
      font-size: 18px;
      transition: all 0.3s ease;
    }

    .input-group input {
      width: 100%;
      padding: 15px 15px 15px 50px;
      border: 2px solid #e1e1e1;
      border-radius: 12px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: #f8f9fa;
      color: #333;
      font-size: 16px; /* 🚀 Previene zoom en iOS */
    }

    .input-group input:focus {
      outline: none;
      border-color: #667eea;
      background: white;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .input-group input:focus + i {
      color: #764ba2;
      transform: translateY(-50%) scale(1.1);
    }

    .input-group input::placeholder {
      color: #999;
    }

    .login-button {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .login-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .login-button:active {
      transform: translateY(0);
    }

    .login-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .login-button:hover::before {
      left: 100%;
    }

    .error-message {
      background: linear-gradient(135deg, #ff6b6b, #ee5a52);
      color: white;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      text-align: center;
      font-weight: 600;
      box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
      animation: shake 0.5s ease-in-out;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    /* 🚀 SECCIÓN ELIMINADA: Los 4 iconos no deseados */
    /* .features { ... } */ 
    /* .feature { ... } */

    .footer {
      text-align: center;
      margin-top: 25px;
      color: #666;
      font-size: 12px;
    }

    /* 🚀 ELIMINADO: .pulse que causaba titileo constante */

    /* Responsive MEJORADO */
    @media (max-width: 480px) {
      body {
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
      }

      .login-container {
        padding: 30px 20px;
        margin: 10px;
        width: 95%;
        max-width: none;
      }

      .logo-section h1 {
        font-size: 22px;
      }

      .logo-section p {
        font-size: 13px;
      }

      .input-group input {
        padding: 14px 14px 14px 45px;
        font-size: 16px; /* 🚀 Mantiene tamaño en móviles */
      }

      .login-button {
        padding: 14px;
        font-size: 16px;
      }
    }

    @media (max-width: 320px) {
      .login-container {
        padding: 20px 15px;
      }
      
      .logo-section h1 {
        font-size: 20px;
      }
      
      .input-group input {
        padding: 12px 12px 12px 40px;
      }
    }

    .floating-shapes {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 0;
    }

    .shape {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float-shapes 20s infinite linear; /* 🚀 Animación más lenta */
    }

    .shape:nth-child(1) {
      width: 80px;
      height: 80px;
      top: 10%;
      left: 10%;
      animation-delay: 0s;
    }

    .shape:nth-child(2) {
      width: 120px;
      height: 120px;
      top: 60%;
      right: 10%;
      animation-delay: -5s;
    }

    .shape:nth-child(3) {
      width: 60px;
      height: 60px;
      bottom: 20%;
      left: 20%;
      animation-delay: -10s;
    }

    @keyframes float-shapes {
      0% {
        transform: translateY(0px) rotate(0deg);
      }
      50% {
        transform: translateY(-10px) rotate(180deg); /* 🚀 Movimiento reducido */
      }
      100% {
        transform: translateY(0px) rotate(360deg);
      }
    }
  </style>
</head>
<body>
  <div class="floating-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
  </div>

  <div class="login-container"> <!-- 🚀 ELIMINADA: clase "pulse" -->
    <div class="logo-section">
      <img src="img/logo.png" alt="Logo institucional" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 fill=%22%23667eea%22 rx=%2220%22/><text x=%2250%22 y=%2255%22 font-family=%22Arial%22 font-size=%2230%22 text-anchor=%22middle%22 fill=%22white%22>🎓</text></svg>'">
      <h1>Sistema "José Agustín Méndez García"</h1>
      <p>Plataforma Educativa Integral</p>
    </div>

    <?php if (isset($_SESSION['login_error'])): ?>
      <div class="error-message">
        <i class="fas fa-exclamation-triangle"></i>
        <?= $_SESSION['login_error']; ?>
      </div>
      <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <form class="login-form" action="validar_login.php" method="POST">
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="correo" placeholder="Correo institucional" required>
      </div>

      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="contraseña" placeholder="Contraseña" required>
      </div>

      <button type="submit" class="login-button">
        <i class="fas fa-sign-in-alt"></i> Ingresar al Sistema
      </button>
    </form>

    <!-- 🚀 SECCIÓN COMPLETAMENTE ELIMINADA: Los 4 iconos no deseados -->
    <!-- <div class="features"> ... </div> -->

    <div class="footer">
      <p>© 2025 Sistema SIPRE. Todos los derechos reservados.</p>
    </div>
  </div>

  <script>
    // Efectos interactivos MEJORADOS
    document.addEventListener('DOMContentLoaded', function() {
      const inputs = document.querySelectorAll('input');
      
      inputs.forEach(input => {
        // 🚀 ELIMINADOS: efectos de escala que causaban movimiento
        
        // Efecto de escritura
        input.addEventListener('input', function() {
          if (this.value.length > 0) {
            this.style.background = 'white';
          } else {
            this.style.background = '#f8f9fa';
          }
        });
      });

      // Efecto de carga del botón
      const loginButton = document.querySelector('.login-button');
      const loginForm = document.querySelector('.login-form');
      
      loginForm.addEventListener('submit', function(e) {
        loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
        loginButton.disabled = true;
      });

      // 🚀 ELIMINADO: efecto parallax que causaba movimiento constante
    });

    // 🚀 PREVENIR ZOOM EN DISPOSITIVOS MÓVILES
    document.addEventListener('touchstart', function(e) {
      if (e.touches.length > 1) {
        e.preventDefault();
      }
    });

    let lastTouchEnd = 0;
    document.addEventListener('touchend', function(e) {
      const now = (new Date()).getTime();
      if (now - lastTouchEnd <= 300) {
        e.preventDefault();
      }
      lastTouchEnd = now;
    }, false);
  </script>
</body>
</html>