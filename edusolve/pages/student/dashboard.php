<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../assets/clases/Usuario.php';
require_once __DIR__ . '/../../assets/dal/UsuariosDAL.php';
require_once __DIR__ . '/../../assets/controllers/AlumnoController.php';

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

// ⚠ Verificar que el ID del alumno exista
if ($idAlumno === null) {
    die("❌ Error: No se encontró el alumno con el nombre y apellido proporcionados.");
}

// ✅ Obtener los datos del alumno
$estudiante = $controller->obtenerPorId((int)$idAlumno);

// ⚠ Validar que se haya encontrado el alumno
if ($estudiante === null) {
    die("❌ Error: No se pudieron obtener los datos del alumno con ID $idAlumno.");
}

// ✅ Obtener materias y calificaciones
$materias = $controller->obtenerMaterias($idAlumno);
$calificaciones = $controller->obtenerCalificaciones($idAlumno);

// ✅ Variables para mostrar en pantalla


$iniciales = strtoupper(substr($estudiante->getNombre(), 0, 1) . substr($estudiante->getApellido(), 0, 1));
$promedio = 0; // se puede calcular luego
$asistencia = 95; // temporal
$totalMaterias = count($materias);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Panel - EduSolve</title>
<link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/preceptor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="icon" href="../../../edusolve/assets/images/escudo.png" type="image/png">
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
                    <li><a href="./materias/listado.php" class="nav-link">Materias</a></li>
                    <li><a href="./perfil/perfil.php" class="user-avatar"><?= $iniciales ?></a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../auth/logout.php'">Cerrar Sesión</button></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="student-container">
    <div class="container">
        <h1 class="admin-title">
Bienvenido, <span class="current-user"><?= htmlspecialchars($estudiante->getNombre() . ' ' . $estudiante->getApellido()) ?></span>
        </h1>

        <div class="stats-grid">
            <div class="stat-card"><h3>Promedio General</h3><p class="stat-number"><?= number_format($promedio, 2) ?></p></div>
            <div class="stat-card"><h3>Asistencia</h3><p class="stat-number"><?= $asistencia ?>%</p></div>
            <div class="stat-card"><h3>Materias</h3><p class="stat-number"><?= $totalMaterias ?></p></div>
        </div>

        <section class="grades-summary">
            <h2>Mis Calificaciones</h2>
            <div class="stat-cards">
                <?php if (!empty($calificaciones)): ?>
                    <?php foreach ($calificaciones as $c): ?>
                        <div class="stat-card">
                            <div class="stat-info">
                                <h3>Materia ID <?= $c['materia_id'] ?></h3>
                                <p><strong>Nota:</strong> <?= $c['calificacion'] ?></p>
                                <small><?= $c['fecha'] ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="stat-card empty"><h3>Sin calificaciones registradas</h3></div>
                <?php endif; ?>
            </div>
        </section>

        <!-- <section class="quick-actions">
            <h2>Acciones Rápidas</h2>
            <div class="actions-grid">
                <a href="./materias/listado.php" class="action-card"><span class="action-icon">📚</span><h3>Ver Mis Materias</h3></a>
                <a href="../certificado/certificado.php" id="downloadCertificate" class="action-card"><span class="action-icon">📜</span><h3>Descargar Certificado</h3></a>
            </div>
        </section> -->
    </div>
</main>

 <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <div class="footer-logo">
                        <img src="/edusolve/assets/images/escudo.png" alt="Escudo de la escuela" width="40">
                        <span>EduSolve</span>
                    </div>
                    <p>Plataforma educativa oficial de la E.E.S.T.N°3</p>
                </div>
                <div class="footer-links">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="../student/materias/listado.php">Materias</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

<script>
document.getElementById('downloadCertificate').addEventListener('click', function() {
    alert('La funcionalidad de descarga de certificados estará disponible próximamente.');
});
</script>
</body>
</html>