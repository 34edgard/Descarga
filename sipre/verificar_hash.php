<?php
$clave_ingresada = 'admin*12345';
$hash_guardado = '$2y$10$S6/ctiAsM0SXlCtVdx7Y0uKpevrrLG4sDai/4sVm819MXUdznbJpa';

if (password_verify($clave_ingresada, $hash_guardado)) {
  echo "✅ La contraseña coincide.";
} else {
  echo "❌ La contraseña NO coincide.";
}
