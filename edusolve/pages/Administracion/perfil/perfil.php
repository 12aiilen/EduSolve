<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../../assets/clases/Usuario.php';
require_once __DIR__ . '/../../../assets/dal/UsuariosDAL.php';
require_once __DIR__ . '/../../../assets/clases/Admin.php';
require_once __DIR__ . '/../../../assets/dal/administracionDAL.php';

// Verifica que haya sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit();
}

// Recupera el objeto usuario
$usuario = unserialize($_SESSION["usuario"]);
$idTipo = (int)$usuario->getIdTiposUsuarios();

// Solo los tipo 3 (administradores) pueden acceder
if ($idTipo !== 3) {
    header("Location: ../auth/login.php");
    exit();
}

//  Calcular iniciales
$iniciales = strtoupper(substr($usuario->getNombre(), 0, 1) . substr($usuario->getApellido(), 0, 1));

//  Obtener fecha de alta del usuario
$fechaAlta = '';
if (method_exists($usuario, 'getFechaAlta')) {
    $fechaAlta = $usuario->getFechaAlta();
} elseif (method_exists($usuario, 'getFechaCreacion')) {
    $fechaAlta = $usuario->getFechaCreacion();
} elseif (method_exists($usuario, 'getCreatedAt')) {
    $fechaAlta = $usuario->getCreatedAt();
} else {
    $fechaAlta = 'No disponible';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Administrador - EduSolve</title>
    <link rel="icon" href="../../../edusolve/assets/images/escudo.png" type="image/png">
    <link rel="stylesheet" href="../../../assets/css/main.css">
</head>
<body>

<header class="header">
    <div class="container">
        <div class="menu">
            <div class="logo-container">
                <img src="/edusolve/assets/images/escudo.png" alt="Escudo" class="header-logo">
                <a href="../../../index.php" class="logo">EduSolve<span class="school-name">Administrador</span></a>
            </div>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="../../../index.php" class="nav-link active">Inicio</a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="admin-container">
    <div class="container">
        <h1 class="admin-title">
            Perfil del Administrador - 
            <span class="current-user"><?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?></span>
        </h1>

        <!-- Información Personal -->
        <div class="profile-section">
            <h2>👤 Información Personal</h2>
            <div class="stat-grid">
                <div class="stat-card">
                    <h3>Nombre Completo</h3>
                    <p><?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?></p>
                </div>

                <div class="stat-card">
                    <h3>Email</h3>
                    <p><?= htmlspecialchars($usuario->getEmail()) ?></p>
                </div>

                <div class="stat-card">
                    <h3>Fecha de Alta</h3>
                    <p><?= htmlspecialchars($fechaAlta) ?></p>
                </div>

                <div class="stat-card">
                    <h3>Iniciales</h3>
                    <p><?= htmlspecialchars($iniciales) ?></p>
                </div>
            </div>
        </div>

        <!-- Botón de volver -->
        <div style="margin-top: 2rem; text-align: center;">
            <a href="Administracion.php" class="button button-primary">
                ← Volver al Inicio
            </a>
        </div>
    </div>
</main>

</body>
</html>