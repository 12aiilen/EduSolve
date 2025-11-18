<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../../assets/clases/Usuario.php';
require_once __DIR__ . '/../../../assets/dal/UsuariosDAL.php';
require_once __DIR__ . '/../../../assets/controllers/AlumnoController.php';

// ✅ Verificar sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Recuperar el usuario logueado
$usuario = unserialize($_SESSION["usuario"]);
$idTipo = (int)$usuario->getIdTiposUsuarios();

// ✅ Solo tipo 5 (alumno) puede acceder
if ($idTipo !== 5) {
    header("Location: ../auth/login.php");
    exit();
}

$controller = new AlumnoController();



// ✅ Buscar el alumno correspondiente al usuario logueado
$idAlumno = $controller->obtenerIdAlumnoPorNombreApellido($usuario->getNombre(), $usuario->getApellido());

// ⚠️ Verificar que el ID del alumno exista
if ($idAlumno === null) {
    die("❌ Error: No se encontró el alumno con el nombre y apellido proporcionados.");
}

// ✅ Obtener los datos del alumno
$estudiante = $controller->obtenerPorId((int)$idAlumno);

// ⚠️ Validar que se haya encontrado el alumno
if ($estudiante === null) {
    die("❌ Error: No se pudieron obtener los datos del alumno con ID $idAlumno.");
}

// ✅ Obtener materias y calificaciones
$materias = $controller->obtenerMaterias($idAlumno);
$calificaciones = $controller->obtenerCalificaciones($idAlumno);
$iniciales = strtoupper(substr($estudiante->getNombre(), 0, 1) . substr($estudiante->getApellido(), 0, 1));


// ✅ Variables para mostrar en pantalla



?>

<?php include __DIR__ . '/../../../assets/partials/navbar.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Estudiante - EduSolve</title>
    <link rel="icon" href="../../../edusolve/assets/images/escudo.png" type="image/png">
    <link rel="stylesheet" href="../../../assets/css/main.css">

</head>
<body>

<header class="header">
    <div class="container">
        <div class="menu">
            <div class="logo-container">
                <img src="/edusolve/assets/images/escudo.png" alt="Escudo" class="header-logo">
                <a href="../../../index.php" class="logo">EduSolve<span class="school-name">Estudiante</span></a>
            </div>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="../dashboard.php" class="nav-link active">Inicio</a></li>
                    <li><a href="../materias/listado.php" class="nav-link">Materias</a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="student-container">
    <div class="container">
        <h1 class="admin-title">
            Perfil del Estudiante - 
            <span class="current-user"><?= htmlspecialchars($estudiante->getNombre() . ' ' . $estudiante->getApellido()) ?></span>
        </h1>

        <div class="stat-card">
            <h3>Nombre</h3>
            <p><?= htmlspecialchars($estudiante->getNombre() . ' ' . $estudiante->getApellido()) ?></p>
        </div>

        <div class="stat-card">
            <h3>DNI</h3>
            <p><?= htmlspecialchars($estudiante->getDNI()) ?></p>
        </div>

        <div class="stat-card">
            <h3>Nacionalidad</h3>
            <p><?= htmlspecialchars($estudiante->getNacionalidad()) ?></p>
        </div>

        <div class="stat-card">
            <h3>Dirección</h3>
            <p><?= htmlspecialchars($estudiante->getDireccion()) ?></p>
        </div>

        <section class="grades-summary">
            <h2>📘 Materias</h2>
            <ul>
<?php if (!empty($materias)): ?>
    <?php foreach ($materias as $m): ?>
        <li>
            <?= htmlspecialchars($m['Nombre'] ?? '') ?> 
            (Año <?= htmlspecialchars($m['Anio'] ?? '') ?>)
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <li>No tiene materias asignadas.</li>
<?php endif; ?>

            </ul>
        </section>

        <section class="grades-summary">
            <h2>📝 Calificaciones</h2>
            <ul>
                <?php if (!empty($calificaciones)): ?>
                    <?php foreach ($calificaciones as $c): ?>
                        <li><?= htmlspecialchars($c['Materia']) ?> - Nota: <?= htmlspecialchars($c['Nota']) ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No hay calificaciones registradas.</li>
                <?php endif; ?>
            </ul>
        </section>

        <!-- Botón de volver -->
        <div style="margin-top: 2rem; text-align: center;">
            <a href="../dashboard.php" class="button button-primary">
                ← Volver al Dashboard
            </a>
        </div>
    </div>
</main>


</body>
</html>
