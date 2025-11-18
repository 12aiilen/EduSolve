<?php
// ==========================================
// 🔹 DASHBOARD DEL PROFESOR (pages/teacher/dashboard.php)
// ==========================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Verificar que haya sesión activa
if (!isset($_SESSION['usuario'])) {
    die("No hay un profesor identificado en sesión.");
}

// Cargar clases necesarias
require_once __DIR__ . '/../../assets/dal/MateriaDAL.php';
require_once __DIR__ . '/../../assets/dal/AsignacionProfesoresDAL.php';
require_once __DIR__ . '/../../assets/clases/Materia.php';
require_once __DIR__ . '/../../assets/clases/Usuario.php';

// Recuperar objeto profesor
$profesor = unserialize($_SESSION['usuario']);
$nombreProfesor = $profesor->getNombre() . ' ' . $profesor->getApellido();
$idProfesor = $profesor->getIdUsuarios();

// Obtener materias asignadas desde la DAL
$asignacionDAL = new AsignacionProfesoresDAL();
$materiaDAL = new MateriaDAL();

$materiasAsignadas = [];

$idsMaterias = $asignacionDAL->obtenerMateriasPorProfesor($idProfesor); // devuelve array de IDs
// echo "<pre>ID PROFESOR EN SESIÓN: $idProfesor</pre>";

if (!empty($idsMaterias)) {
    $materiasAsignadas = $materiaDAL->getByIds($idsMaterias);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel del Profesor - EduSolve</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/preceptor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../../assets/images/escudo.png" type="image/png">
<style>
<?php
$cssPath = __DIR__ . '/../../assets/css/profesor.css';
if (file_exists($cssPath)) { echo file_get_contents($cssPath); }
?>
/* Sección de materias asignadas */
.materias-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    padding: 2rem;
    margin-top: 2rem;
    font-family: 'Inter', sans-serif;
}

.materias-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.materias-title i {
    color: var(--primary, #334155);
    font-size: 1.2rem;
}

.materias-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 0.75rem;
}

.materia-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    background: #f9fafb;
    padding: 0.8rem 1rem;
    border-radius: 8px;
    color: #334155;
    font-weight: 500;
    transition: background 0.2s, transform 0.2s;
}

.materia-item i {
    color: #10b981;
}

.materia-item:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

/* Estado vacío */
.no-materias {
    text-align: center;
    color: #6b7280;
    background: #f9fafb;
    border-radius: 8px;
    padding: 2rem 1rem;
    font-size: 1rem;
}

.no-materias i {
    font-size: 2rem;
    color: #94a3b8;
    margin-bottom: 0.5rem;
    display: block;
}

/* Contenedor principal del dashboard */
.main-content {
    max-width: 1200px;   /* limita el ancho total */
    margin: 40px auto;   /* centra y deja espacio arriba/abajo */
    padding: 0 80px;     /* 👈 espacio lateral generoso */
    box-sizing: border-box;
}

/* Opcional: estilo interno si usás un .container dentro */
.main-content .container {
    width: 100%;
    margin: 0 auto;
}


</style>
</head>

<body><header class="header">
    <div class="container">
        <div class="menu">
            <div class="logo-container">
                <img src="/edusolve/assets/images/escudo.png" alt="Escudo de la escuela" class="header-logo">
                <a href="dashboard.php" class="logo">
                    EduSolve<span class="school-name">Panel Profesor</span>
                </a>
            </div>

            <nav class="nav" aria-label="Navegación del Profesor">
                <ul class="nav-list">
                    <li><a href="./dashboard.php" class="nav-link active">Inicio</a></li>
                    <li><a href="./calificaciones/listado.php" class="nav-link">Calificaciones</a></li>
                    <li><a href="./calificaciones/agendarClase.php" class="nav-link">Agendar Clase</a></li>
                    <li><a href="./calificaciones/ver_clases.php" class="nav-link">Mis Clases</a></li>
                    <li><a href="./calificaciones/subirMaterial.php" class="nav-link">Subir Material</a></li>
                    <li><a href="./perfil/perfil.php" class="user-avatar">A</a></li>
                    <li>
                        <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../auth/logout.php'">
                            Cerrar Sesión
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>


<main class="main-content">
  <div class="content">

<div class="dashboard-grid">

    <!-- Tarjeta de Bienvenida -->
<div class="card welcome-card">
    <div class="welcome-header">
        <div>

            <div class="container">
        <h1 class="admin-title">
            <h1>Panel de Profesores</h1>
               <h2>Bienvenido, <?php echo htmlspecialchars($nombreProfesor); ?></h2> 
            </h1>

            <div class="materias-section">
                <?php if (!empty($materiasAsignadas)): ?>
                    <h3 class="materias-title">
                        <i class="fa-solid fa-book"></i> Materias asignadas
                    </h3>
                    <ul class="materias-list">
                        <?php foreach ($materiasAsignadas as $m): ?>
                            <li class="materia-item">
                                <i class="fa-solid fa-check"></i></i>
                                <?php echo htmlspecialchars($m->getNombre()); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="no-materias">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <p><em>No tienes materias asignadas actualmente.</em></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<br>
<br>


        

        <!-- Estadísticas rápidas -->
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-number">24</div><div class="stat-label">Estudiantes Activos</div></div>
            <div class="stat-card"><div class="stat-number">156</div><div class="stat-label">Calificaciones Registradas</div></div>
            <div class="stat-card"><div class="stat-number">8</div><div class="stat-label">Clases este Mes</div></div>
            <div class="stat-card"><div class="stat-number">0</div><div class="stat-label">Materiales Subidos</div></div>
        </div>
    </div>

    <!-- Gestión Académica -->
    <div class="card">
        <section class="features">
            <h2 class="section-title">Gestión Académica</h2>
            <div class="features-grid">
                <a href="./calificaciones/agregar_calificacion.php" class="action-card">
                    <div class="feature-icon"><i class="fa-solid fa-plus"></i></div>
                    <h3>Agregar Calificación</h3>
                    <p>Registra nuevas calificaciones y evaluaciones para tus estudiantes.</p>
                </a>
                <a href="./calificaciones/listado.php" class="action-card">
                    <div class="feature-icon"><i class="fa-solid fa-building-columns"></i></div>
                    <h3>Ver Calificaciones</h3>
                    <p>Consulta el historial completo y progreso académico de tus estudiantes.</p>
                </a>
                <a href="./calificaciones/agendarClase.php" class="action-card">
                    <div class="feature-icon"><i class="fa-solid fa-glasses"></i></div>
                    <h3>Agendar Clase</h3>
                    <p>Programa nuevas sesiones de clase y gestiona tu calendario académico.</p>
                </a>

                <a href="./calificaciones/ver_clases.php" class="action-card">
                    <div class="feature-icon"><i class="fa-solid fa-school"></i></div>
                    <h3>Mis Clases</h3>
                    <p>Consultar clases agendadas</p>
                </a>

                <a href="./calificaciones/subirMaterial.php" class="action-card">
                    <div class="feature-icon"><i class="fa-solid fa-file-signature"></i></div>
                    <h3>Subir Material</h3>
                    <p>Comparte material didáctico y recursos educativos con tus estudiantes.</p>
                </a>
            </div>
        </section>
    </div>

</div>
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
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

<script>
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const navLinks = document.getElementById('navLinks');
const userInfo = document.getElementById('userInfo');
mobileMenuBtn.addEventListener('click', () => {
    mobileMenuBtn.classList.toggle('active');
    navLinks.classList.toggle('active');
    userInfo.classList.toggle('active');
});
</script>
</body>
</html>
