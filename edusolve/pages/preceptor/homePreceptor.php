<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../../assets/clases/Usuario.php';
require_once __DIR__ . '/../../assets/dal/PreceptorDAL.php';
require_once __DIR__ . '/../../assets/dal/AlumnoDAL.php';
require_once __DIR__ . '/../../assets/dal/CursoDAL.php';
require_once __DIR__ . '/../../assets/dal/InformeAsistenciaDAL.php';
require_once __DIR__ . '/../../assets/dal/ActividadDAL.php';

// -----------------------------
// 🔒 Validar sesión
// -----------------------------
if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit();
}

// -----------------------------
// 👤 Usuario logueado
// -----------------------------
$usuario = unserialize($_SESSION["usuario"]);
$nombreCompleto = htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido());
$idPreceptor = $usuario->getIdUsuarios();
$idTipo = (int)$usuario->getIdTiposUsuarios();

// -----------------------------
// 🚫 Bloqueo de acceso no autorizado
// -----------------------------
// Solo los tipo 1 (preceptores) pueden entrar a este módulo
if ($idTipo !== 1) {
    header("Location: ../auth/login.php");
    exit();
}

// -----------------------------
// 📊 Inicializar DALs
// -----------------------------
$preceptorDAL = new PreceptorDAL();
$alumnosDAL = new AlumnoDAL();
$cursoDAL = new CursoDAL();
$informeDAL = new InformeAsistenciaDAL();
$actividadDAL = new ActividadDAL();

// -----------------------------
// 📚 Consultas a la base de datos
// -----------------------------

// 🔹 Obtener los estudiantes del preceptor actual
$estudiantes = $alumnosDAL->obtenerEstudiantesPorPreceptor($idPreceptor);
$totalEstudiantes = count($estudiantes);

// 🔹 Informe de asistencia por preceptor
$informeAsistencia = $informeDAL->generarInformePorPreceptor($idPreceptor);

$aprobados = $alumnosDAL->getAprobados();
$promedioGeneral = $alumnosDAL->getPromedioGeneral();
$destacados = $alumnosDAL->getDestacados();

// 🔹 Actividad reciente del preceptor logueado
$actividades = $actividadDAL->obtenerActividadesPorUsuario($idPreceptor, 10);
$iniciales = strtoupper(substr($usuario->getNombre(), 0, 1) . substr($usuario->getApellido(), 0, 1));


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Preceptor - EduSolve</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/preceptor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../../assets/images/escudo.png" type="image/png">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="menu">
                <div class="logo-container">
                    <img src="../../assets/images/escudo.png" alt="Escudo de la escuela" class="header-logo">
                    <a href="dashboard.php" class="logo">EduSolve<span class="school-name">Panel Directivo</span></a>
                </div>
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="homePreceptor.php" class="nav-link active">Inicio</a></li>
                        <li><a href="estudiantes/listado.php" class="nav-link">Estudiantes</a></li>
                        <li><a href="materias/listado.php" class="nav-link">Materias</a></li>
                        <li><a href="perfil/perfil.php" class="user-avatar"><?= $iniciales ?></a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../auth/logout.php'">Cerrar Sesión</button></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="preceptor-panel">
        <div class="container">
            <h1 class="preceptor-title">Panel de Preceptoría</h1>
            <h2>Bienvenido, <?= $nombreCompleto ?></h2>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Estudiantes</h3>
                    <p><?= $totalEstudiantes ?></p>
                </div>
                <div class="stat-card">
                    <h3>Aprobados</h3>
                    <p><?= count($aprobados) ?></p>
                </div>
                <div class="stat-card">
                    <h3>Promedio General</h3>
                    <p><?= number_format($promedioGeneral, 1) ?></p>
                </div>
                <div class="stat-card">
                    <h3>Destacados</h3>
                    <p><?= count($destacados) ?></p>
                </div>
            </div>
            
            <section class="quick-actions">
                <h2><i class="fa-solid fa-bolt"></i> Acciones Rápidas</h2>
                <div class="actions-grid">
                    <a href="estudiantes/listado.php" class="action-card">
                        <h3><i class="fa-solid fa-school"></i> Gestión de Estudiantes</h3>
                    </a>
                    <a href="materias/listado.php" class="action-card">
                        <h3><i class="fa-solid fa-book"></i> Gestión de Materias</h3>
                    </a>
                    <a href="materias/asignar.php" class="action-card">
                        <h3><i class="fa-solid fa-chalkboard-user"></i> Asignar Profesores</h3>
                    </a>
                </div>
            </section>
            
            <section class="recent-activity">
                <h2><i class="fa-solid fa-check"></i> Actividad Reciente 'Asignar Profesores'.</h2>
                <table class="preceptor-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Acción</th>
                            <th>Usuario</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($actividades)): ?>
                            <?php foreach ($actividades as $a): ?>
                                <tr>
                                    <td><?= htmlspecialchars($a['fecha']) ?></td>
                                    <td><?= htmlspecialchars($a['descripcion']) ?></td>
                                    <td><?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) ?></td>
                                    <td>-</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align:center;">Sin actividad reciente.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <div class="footer-logo">
                        <img src="../../assets/images/escudo.png" alt="Escudo de la escuela" width="40">
                        <span>EduSolve</span>
                    </div>
                    <p>Plataforma educativa oficial de la E.E.S.T.N°3</p>
                </div>
                <div class="footer-links">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="../../index.php">Inicio</a></li>
                        <li><a href="../general/materias.php">Materias</a></li>
                        <li><a href="../general/contacto.php">Contacto</a></li>
                        <li><a href="../general/informacion.php">Acerca de</a></li>
                        <li><a href="../auth/login.html">Acceso</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

</body>
</html>
