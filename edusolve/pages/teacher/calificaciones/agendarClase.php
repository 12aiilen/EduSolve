<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../../../assets/clases/Usuario.php';
require_once __DIR__ . '/../../../assets/clases/Clase.php';
require_once __DIR__ . '/../../../assets/bll/ClaseBLL.php';
require_once __DIR__ . '/../../../assets/bll/MateriaBLL.php';
require_once __DIR__ . '/../../../assets/bll/CursoBLL.php';

// --- Verificación de sesión y permisos ---
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../auth/login.php');
    exit;
}

$profesor = unserialize($_SESSION['usuario']);
if ($profesor->getIdTiposUsuarios() != 4) {
    die("Acceso denegado: No tienes permisos de profesor.");
}

$profesor_id = $profesor->getIdUsuarios();
$nombreProfesor = $profesor->getNombre() . ' ' . $profesor->getApellido();

// --- Instanciamos BLLs ---
$claseBLL = new ClaseBLL();
$materiaBLL = new MateriaBLL();
$cursoBLL = new CursoBLL();

// --- Inicializar mensaje ---
$mensaje = '';
$tipoMensaje = '';

// --- Obtener materias y cursos asignados ---
$materias = $materiaBLL->obtenerMateriasAsignadas($profesor_id);
$cursos = $cursoBLL->getAllCursos($profesor_id); // <-- recomiendo traer solo los cursos de este profesor

// --- Procesar formulario ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? '';
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    $hora_fin = $_POST['hora_fin'] ?? '';
    $materia_id = $_POST['materia_id'] ?? '';
    $curso_id = $_POST['curso_id'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    if (empty($fecha) || empty($hora_inicio) || empty($hora_fin) || empty($materia_id) || empty($curso_id)) {
        $mensaje = "Error: faltan datos obligatorios.";
        $tipoMensaje = "danger";
    } else {
        // Creamos el objeto Clase
        $clase = new Clase();
        $clase->setProfesorId($profesor_id);
        $clase->setMateriaId($materia_id);
        $clase->setCursoId($curso_id);
        $clase->setFecha($fecha);
        $clase->setHoraInicio($hora_inicio);
        $clase->setHoraFin($hora_fin);
        $clase->setDescripcion($descripcion);

        // Pasamos el objeto al BLL
        $resultado = $claseBLL->agendarClase($clase);

        if (!empty($resultado['success'])) {
            $mensaje = $resultado['success'];
            $tipoMensaje = "success";
        } else {
            $mensaje = $resultado['error'] ?? "Error desconocido al agendar la clase.";
            $tipoMensaje = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Clase - EduSolve</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="/assets/images/escudo.png" type="image/png">
    <style>


        
        /* RESET Y ESTRUCTURA PRINCIPAL */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text, #333);
            background-color: #f9f9f9;
        }
        
        /* HEADER FIJADO CORRECTAMENTE - IGUAL QUE EN DASHBOARD */
        .header {
            background-color: var(--primary, #2c3e50);
            color: white;
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: var(--shadow, 0 4px 6px rgba(0, 0, 0, 0.1));
            width: 100%;
            height: 80px;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 100%;
        }
        
        .menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .header-logo {
            width: 40px;
            height: 40px;
        }
        
        .logo {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .school-name {
            font-size: 1rem;
            font-weight: 400;
            margin-left: 0.5rem;
            opacity: 0.9;
        }
        
        .nav-list {
            display: flex;
            list-style: none;
            gap: 1.5rem;
            align-items: center;
        }
        
        .nav-list a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
            transition: var(--transition, all 0.3s ease);
        }
        
        .nav-list a:hover,
        .nav-list a.active {
            color: var(--secondary, #3498db);
        }
        
        .nav-list a.active::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background: var(--secondary, #3498db);
            bottom: 0;
            left: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--secondary, #3498db);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
        
        /* CONTENIDO PRINCIPAL - ESPACIO CORREGIDO - IGUAL QUE EN DASHBOARD */
        .main-content {
            flex: 1;
            width: 100%;
            margin-top: 80px;
            padding-top: 2rem;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* TARJETAS DEL FORMULARIO */
        .dashboard-grid {
            display: grid;
            gap: 2.5rem;
            margin-top: 1rem;
        }
        
        .card {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow, 0 4px 12px rgba(0, 0, 0, 0.08));
            margin-bottom: 1rem;
        }
        
        .admin-title {
            font-size: 2.2rem;
            color: var(--primary, #2c3e50);
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
        }
        
        /* ESTILOS DEL FORMULARIO */
        .admin-form {
            margin-top: 2rem;
        }
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        .row-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--primary, #2c3e50);
            font-size: 1rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--secondary, #3498db);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        /* ALERTAS */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* BOTONES - IGUAL QUE EN DASHBOARD */
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-start;
            margin-top: 2rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition, all 0.3s ease);
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }
        
        .btn-primary {
            background-color: var(--secondary, #3498db);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: white;
            color: var(--primary, #2c3e50);
            border: 1px solid var(--primary, #2c3e50);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary, #2c3e50);
            color: white;
        }
        
        /* FOOTER SIEMPRE ABAJO - IGUAL QUE EN DASHBOARD */
        .footer {
            background-color: var(--primary, #2c3e50);
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
            margin-top: auto;
        }
        
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* MENÚ MÓVIL - IGUAL QUE EN DASHBOARD */
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }
        
        .bar {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 3px 0;
            transition: 0.3s;
        }
        
        /* RESPONSIVE - IGUAL QUE EN DASHBOARD */
        @media (max-width: 768px) {
            .menu {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .nav-list {
                margin-top: 1rem;
                flex-direction: column;
                width: 100%;
                gap: 1rem;
                display: none;
            }
            
            .nav-list.active {
                display: flex;
            }
            
            .user-info {
                margin-top: 1rem;
                width: 100%;
                justify-content: flex-start;
                display: none;
            }
            
            .user-info.active {
                display: flex;
            }
            
            .mobile-menu-btn {
                display: flex;
                position: absolute;
                right: 1.5rem;
                top: 1rem;
            }
            
            .mobile-menu-btn.active .bar:nth-child(1) {
                transform: rotate(-45deg) translate(-5px, 6px);
            }
            
            .mobile-menu-btn.active .bar:nth-child(2) {
                opacity: 0;
            }
            
            .mobile-menu-btn.active .bar:nth-child(3) {
                transform: rotate(45deg) translate(-5px, -6px);
            }
            
            .row-group {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .card {
                padding: 2rem;
            }
            
            .main-content {
                margin-top: 120px;
            }
            
            .admin-title {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .card {
                padding: 1.5rem;
            }
            
            .admin-title {
                font-size: 1.5rem;
            }
        }
    </style> </style>
</head>
<body>
<header class="header" role="banner">
    <div class="header-container">
        <div class="menu">
            <div class="logo-container">
                <img src="/edusolve/assets/images/escudo.png" class="header-logo" alt="Escudo de la escuela">
                <a href="../dashboard.php" class="logo">
                    <i class="fas fa-graduation-cap"></i>
                    EduSolve<span class="school-name">E.E.S.T.N°3</span>
                </a>
            </div>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
            </button>

            <nav class="nav" aria-label="Navegación del Profesor">
                <ul class="nav-list" id="navLinks">
                    <li><a href="../dashboard.php">Inicio</a></li>
                    <li><a href="listado.php">Calificaciones</a></li>
                    <li><a href="agendarClase.php" class="active">Agendar Clase</a></li>
                    <li><a href="ver_clases.php">Mis Clases</a></li>
                    <li><a href="subirMaterial.php">Subir Material</a></li>
                    <li><a href="../Perfil/Perfil.php" class="user-avatar"><?= strtoupper(substr($profesor->getNombre(), 0, 1)) ?></a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="container">
        <div class="dashboard-grid">
            <div class="card">
                <h1 class="admin-title">Agendar Clase</h1>

                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?= $tipoMensaje === 'success' ? 'success' : 'danger' ?>">
                        <i class="fas fa-<?= $tipoMensaje === 'success' ? 'check' : 'exclamation' ?>-circle"></i>
                        <?= htmlspecialchars($mensaje) ?>
                    </div>
                <?php endif; ?>

                <div class="admin-form">
                    <form method="POST" action="">
                        <div class="form-group row-group">
                            <div class="col">
                                <label class="form-label">Fecha *</label>
                                <input type="date" name="fecha" class="form-control" 
                                       value="<?= htmlspecialchars($_POST['fecha'] ?? date('Y-m-d')) ?>" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Hora Inicio *</label>
                                <input type="time" name="hora_inicio" class="form-control" 
                                       value="<?= htmlspecialchars($_POST['hora_inicio'] ?? '08:00') ?>" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Hora Fin *</label>
                                <input type="time" name="hora_fin" class="form-control" 
                                       value="<?= htmlspecialchars($_POST['hora_fin'] ?? '09:00') ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Materia *</label>
<div class="form-group">
    <label class="form-label">Materia *</label>
    <select name="materia_id" class="form-control" required>
        <option value="">Seleccione una materia</option>
        <?php if (!empty($materiasAsignadas)): ?>
            <?php foreach ($materiasAsignadas as $materia): ?>
                <option value="<?= $materia->getId() ?>"
                    <?= (($_POST['materia_id'] ?? '') == $materia->getId()) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($materia->getNombre()) ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="" disabled>No tienes materias asignadas</option>
        <?php endif; ?>
    </select>
    <?php if (empty($materiasAsignadas)): ?>
        <small class="form-text text-danger">No tienes materias asignadas. Contacta al administrador.</small>
    <?php endif; ?>
</div>


                            <?php if (empty($materias)): ?>
                                <small class="form-text text-danger">No tienes materias asignadas. Contacta al administrador.</small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Curso *</label>
<select name="curso_id" class="form-control" required>
    <option value="">Seleccione un curso</option>
    <?php if (!empty($cursos)): ?>
        <?php foreach ($cursos as $curso): ?>
            <option value="<?= $curso['idCursos'] ?>">
                <?= htmlspecialchars($curso['Año']) ?>°<?= htmlspecialchars($curso['Division']) ?>
            </option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="" disabled>No tienes cursos asignados</option>
    <?php endif; ?>
</select>
<?php if (empty($cursos)): ?>
    <small class="form-text text-danger">No tienes cursos asignados. Contacta al administrador.</small>
<?php endif; ?>

                        </div>

                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Agregue una descripción opcional para la clase..."><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-calendar-alt"></i> Agendar Clase</button>
                            <a href="ver_clases.php" class="btn btn-secondary"><i class="fas fa-list"></i> Ver Mis Clases</a>
                            <a href="../dashboard.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                        </div>
                    </form>
                </div>
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
    if (userInfo) userInfo.classList.toggle('active');
});

window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.header');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

document.querySelectorAll('.nav-list a').forEach(link => {
    link.addEventListener('click', () => {
        mobileMenuBtn.classList.remove('active');
        navLinks.classList.remove('active');
        if (userInfo) userInfo.classList.remove('active');
    });
});
</script>
</body>
</html>
