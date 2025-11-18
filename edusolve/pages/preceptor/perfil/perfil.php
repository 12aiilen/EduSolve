<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../../assets/clases/Usuario.php';

// ✅ Verificar sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../auth/login.php");
    exit();
}

// ✅ Recuperar el usuario logueado
$usuario = unserialize($_SESSION["usuario"]);
$idTipo = (int)$usuario->getIdTiposUsuarios();

// ✅ Solo preceptor (tipo 4) puede acceder
if ($idTipo !== 1) {
    header("Location: ../../auth/login.php");
    exit();
}

// Iniciales del preceptor para avatar
$iniciales = strtoupper(substr($usuario->getNombre(), 0, 1) . substr($usuario->getApellido(), 0, 1));

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Preceptor - EduSolve</title>
    <link rel="icon" href="../../../assets/images/escudo.png" type="image/png">
    <link rel="stylesheet" href="../../../assets/css/main.css">
</head>
<body>

<header class="header">
    <div class="container">
        <div class="menu">
            <div class="logo-container">
                <img src="../../../assets/images/escudo.png" alt="Escudo" class="header-logo">
                <a href="../../../index.php" class="logo">EduSolve <span class="school-name">Preceptor</span></a>
            </div>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="../homePreceptor.php" class="nav-link">Inicio</a></li>
                    <li><a href="perfil.php" class="nav-link active">Perfil</a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="student-container">
    <div class="container">
        <h1 class="admin-title">
            Perfil del Preceptor - 
            <span class="current-user"><?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?></span>
        </h1>

        <div class="stat-card">
            <h3>Nombre</h3>
            <p><?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()) ?></p>
        </div>

        <div class="stat-card">
            <h3>DNI</h3>
            <p><?= htmlspecialchars($usuario->getDni()) ?></p>
        </div>

        <div class="stat-card">
            <h3>Email</h3>
            <p><?= htmlspecialchars($usuario->getEmail()) ?></p>
        </div>

        <div class="stat-card">
            <h3>Tipo de usuario</h3>
            <p>Preceptor</p>
        </div>


        <div style="margin-top: 2rem; text-align: center;">
            <a href="../homePreceptor.php" class="button button-primary">
                ← Volver al Dashboard
            </a>
        </div>
    </div>
</main>

</body>
</html>
