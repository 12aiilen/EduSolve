<?php
$password = 'brisa';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo '<pre>';
echo "Contraseña (entrada): " . htmlspecialchars($password, ENT_QUOTES) . PHP_EOL;
echo "Hash (guardar en DB): " . $hash . PHP_EOL;
echo '</pre>';
?>