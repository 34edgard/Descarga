<?php
session_start();
session_destroy();
echo "<script>
  alert('Sesión cerrada correctamente');
  window.location.href = 'login.php';
</script>";
exit;