<?php
// ==========================================
// 🔹 PERFIL DEL PROFESOR (pages/teacher/Perfil/Perfil.php)
// ==========================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Verificar que haya sesión activa
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../auth/login.php');
    exit;
}

// Cargar las clases necesarias ANTES de deserializar
require_once __DIR__ . '/../../../assets/clases/Usuario.php';
require_once __DIR__ . '/../../../assets/dal/AsignacionProfesoresDAL.php';
require_once __DIR__ . '/../../../assets/dal/MateriaDAL.php';

// Recuperar objeto profesor
$profesor = unserialize($_SESSION['usuario']);
$nombreProfesor = $profesor->getNombre() . ' ' . $profesor->getApellido();
$idProfesor = $profesor->getIdUsuarios();
$emailProfesor = $profesor->getEmail();
$dniProfesor = $profesor->getDNI();

// Obtener iniciales para el avatar
$iniciales = strtoupper(substr($profesor->getNombre(), 0, 1) . substr($profesor->getApellido(), 0, 1));

// OBTENER FECHA DE ALTA - Usar fecha actual como referencia
$fechaAltaFormateada = date('d/m/Y');

// Obtener materias asignadas
$asignacionDAL = new AsignacionProfesoresDAL();
$materiaDAL = new MateriaDAL();

$materiasAsignadas = [];
$idsMaterias = $asignacionDAL->obtenerMateriasPorProfesor($idProfesor);

if (!empty($idsMaterias)) {
    $materiasAsignadas = $materiaDAL->getByIds($idsMaterias);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Profesor - EduSolve</title>
    <link rel="icon" href="../../../assets/images/escudo.png" type="image/png">
    <link rel="stylesheet" href="../../../assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>

<header class="header">
    <div class="container">
        <div class="menu">
            <div class="logo-container">
                <img src="/edusolve/assets/images/escudo.png" alt="Escudo" class="header-logo">
                <a href="../../../index.php" class="logo">EduSolve<span class="school-name">Profesor</span></a>
            </div>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="../../../index.php" class="nav-link">Inicio</a></li>
                    <li><a href="../dashboard.php" class="nav-link">Panel</a></li>
                    <li><a href="../listado.php" class="nav-link">Calificaciones</a></li>
                    <li><a href="../subirMaterial.php" class="nav-link">Material</a></li>
                    <!-- <li><a href="../Perfil/Perfil.php" class="nav-link">Perfil</a></li> -->
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="admin-container">
    <div class="container">
        <h1 class="admin-title">
            Perfil del Profesor - 
        </h1>

        <!-- Información Personal -->
        <div class="profile-section">
            <h2><i class="fa-solid fa-person"></i> Información Personal</h2>
            <div class="stat-grid">
                <div class="stat-card">
                    <h3>Nombre Completo</h3>
                    <p><?php echo htmlspecialchars($nombreProfesor); ?></p>
                </div>

                <div class="stat-card">
                    <h3>Email</h3>
                    <p><?php echo htmlspecialchars($emailProfesor); ?></p>
                </div>

                <div class="stat-card">
                    <h3>DNI</h3>
                    <p><?php echo htmlspecialchars($dniProfesor) ?></p>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
