<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Redirigir si ya está logueado
if (isset($_SESSION['TipoUsuario']) && !empty($_SESSION['TipoUsuario'])) {
    $tipo = (int)$_SESSION['TipoUsuario'];
    switch ($tipo) {
        case 3: header("Location: ../Administracion/Administracion.php"); exit;
        case 4: header("Location: ../preceptor/homePreceptor.php"); exit;
        case 5: header("Location: ../../student/dashboard.php"); exit;
        case 6: header("Location: ../teacher/dashboard.php"); exit;
    }
}

// Mostrar mensaje de error si existe en la sesión
$error = '';
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Limpiar mensaje para que no persista
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>Inicio de sesión - EduSolve</title>
   <link rel="stylesheet" type="text/css" href="../../assets/css/main.css">
   <link rel="icon" href="../../../edusolve/assets/images/escudo.png" type="image/png">
   <link rel="stylesheet" href="../../assets/css/bootstrap.css">
   <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
</head>

<body>
<div class="login-container">
    <div class="login-box">
        <form class="login-form" method="POST" action="loginController.php">
            <img src="../../../edusolve/assets/images/escudo.png" alt="EduSolve" style="width:100px; margin-bottom:1rem;">
            <h2 class="login-title">BIENVENIDA/O</h2>

            <?php if ($error): ?>
                <div class="login-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control" required>
            </div>

            <input type="submit" name="btningresar" value="INICIAR SESIÓN" class="login-button">
            <a href="../../index.php" class="btn btn-secondary" style="margin-top:1rem; display:inline-block;">VOLVER</a>
        </form>
    </div>
</div>

<script src="../../assets/js/fontawesome.js"></script>
<script src="../../assets/js/main.js"></script>
<script src="../../assets/js/main2.js"></script>
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.js"></script>
<script src="../../assets/js/bootstrap.bundle.js"></script>
</body>
</html>
