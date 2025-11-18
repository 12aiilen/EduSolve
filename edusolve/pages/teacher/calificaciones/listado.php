<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../assets/dal/CalificacionDAL.php';
require_once __DIR__ . '/../../../assets/config/AbstractMapper.php';
$calificacionDAL = new CalificacionDAL();
$calificaciones = $calificacionDAL->getAllCalificaciones();

// Obtener nombres reales de estudiantes, profesores, materias y tipos de evaluación
$estudiantes = $calificacionDAL->getEstudiantes();
$profesores = $calificacionDAL->getProfesores();
$materias = $calificacionDAL->getMaterias();
$tiposEvaluacion = $calificacionDAL->getTiposEvaluacion();

// Crear arrays asociativos para búsqueda rápida
$nombres_estudiantes = [];
foreach ($estudiantes as $est) {
    $nombres_estudiantes[$est['id']] = $est['Nombre'] . ' ' . $est['Apellido'] . ' (' . $est['anio'] . '°' . $est['division'] . ')';
}

$nombres_profesores = [];
foreach ($profesores as $prof) {
    $nombres_profesores[$prof['id']] = $prof['Nombre'] . ' ' . $prof['Apellido'];
}

$nombres_materias = [];
foreach ($materias as $mat) {
    $nombres_materias[$mat['id']] = $mat['nombre'];
}

$nombres_tipos_evaluacion = [];
foreach ($tiposEvaluacion as $tipo) {
    $nombres_tipos_evaluacion[$tipo['id']] = $tipo['nombre'];
}

// Datos del profesor (ejemplo)
$nombre_profesor = "María González";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Calificaciones - EduSolve</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/preceptor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../../assets/images/escudo.png" type="image/png">
  <style>
        <?php 
        // Incluir el CSS del profesor
        $cssPath = __DIR__ . '/../assets/css/profesor.css';
        if (file_exists($cssPath)) {
            echo file_get_contents($cssPath);
        }
        ?>
        
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
        
        /* HEADER FIJADO CORRECTAMENTE */
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
            height: 80px; /* Altura fija para calcular mejor el espacio */
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
        
        /* CONTENIDO PRINCIPAL - ESPACIO CORREGIDO */
        .main-content {
            flex: 1;
            width: 100%;
            margin-top: 80px; /* Exactamente la altura del header */
            padding-top: 2rem; /* Espacio adicional para separar del header */
        }
        
        .admin-panel {
            padding: 0 0 2rem 0; /* Quitamos padding-top porque ya lo tiene main-content */
            min-height: calc(100vh - 200px);
        }
        
        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* ENCABEZADO ADMIN */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .admin-title {
            font-size: 2.2rem;
            color: var(--primary, #2c3e50);
            margin: 0;
            padding-top: 1rem; /* Espacio adicional */
        }
        
        .actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        /* MENSAJE DE BIENVENIDA */
        .welcome-message {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: var(--shadow, 0 4px 6px rgba(0, 0, 0, 0.1));
            margin-bottom: 2rem;
            border-left: 4px solid var(--secondary, #3498db);
            margin-top: 1rem; /* Espacio adicional */
        }
        
        .current-user {
            font-weight: 600;
            color: var(--primary, #2c3e50);
        }
        
        /* BÚSQUEDA */
        .search-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin-top: 1rem; /* Espacio adicional */
        }
        
        .search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light, #7f8c8d);
        }
        
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: var(--transition, all 0.3s ease);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--secondary, #3498db);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        /* BOTONES */
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
        
        /* TABLA */
        .card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: var(--shadow, 0 4px 6px rgba(0, 0, 0, 0.1));
            overflow: hidden;
            margin-bottom: 2rem;
            margin-top: 1rem; /* Espacio adicional */
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark, #2c3e50);
            position: sticky;
            top: 0;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        /* BADGES */
        .badge {
            display: inline-block;
            padding: 0.35rem 0.65rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: rgba(39, 174, 96, 0.15);
            color: var(--success, #27ae60);
        }
        
        .badge-warning {
            background-color: rgba(243, 156, 18, 0.15);
            color: var(--warning, #f39c12);
        }
        
        .badge-danger {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--danger, #e74c3c);
        }
        
        /* ACCIONES TABLA */
        .actions-cell {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-btn {
            padding: 0.5rem;
            border-radius: 4px;
            color: white;
            border: none;
            cursor: pointer;
            transition: var(--transition, all 0.3s ease);
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .edit-btn {
            background: var(--secondary, #3498db);
        }
        
        .edit-btn:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }
        
        .delete-btn {
            background: var(--danger, #e74c3c);
        }
        
        .delete-btn:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }
        
        /* ESTADO VACÍO */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-light, #7f8c8d);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ddd;
        }
        
        /* FOOTER SIEMPRE ABAJO */
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
        
        /* MENÚ MÓVIL */
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
        
        /* RESPONSIVE */
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
            
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .actions {
                width: 100%;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .search-container {
                max-width: 100%;
            }
            
            .main-content {
                margin-top: 120px; /* Más espacio para el header en móvil */
            }
            
            th, td {
                padding: 0.75rem 0.5rem;
            }
            
            .actions-cell {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }
        }
        
        @media (max-width: 480px) {
            .card {
                padding: 1.5rem;
            }
            
            .admin-title {
                font-size: 1.8rem;
            }
            
            .welcome-message {
                padding: 1rem;
            }
         }
            .btn.btn-primary{
                background:#334155
            }
        
    </style>
</head>
<body>
    <header class="header" role="banner">
    <div class="container">
        <div class="menu">
            <div class="logo-container">
                <img src="/edusolve/assets/images/escudo.png" alt="Escudo de la escuela" class="header-logo">
                <a href="dashboard.php" class="logo">
                    EduSolve<span class="school-name">Panel Profesor</span>
                </a>
            </div>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
                
            <nav class="nav" aria-label="Navegación del Profesor">
                <ul class="nav-list">
                    <li><a href="../dashboard.php" class="nav-link active">Inicio</a></li>
                    <li><a href="calificaciones/listado.php" class="nav-link">Calificaciones</a></li>
                    <li><a href="calificaciones/agendarClase.php" class="nav-link">Agendar Clase</a></li>
                    <li><a href="calificaciones/ver_clases.php" class="nav-link">Mis Clases</a></li>
                    <li><a href="calificaciones/subirMaterial.php" class="nav-link">Subir Material</a></li>
                    <li><a href="../perfil/perfil.php" class="user-avatar">A</a></li>
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
        <div class="admin-panel">
            <div class="container">
                <!-- Admin Header -->
                <div class="admin-header">
                    <h1 class="admin-title">Gestión de Calificaciones</h1>
                    <div class="actions">
                        <a href="./agregar_calificacion.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva Calificación
                        </a>
                        <a href="../dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>
                </div>

                <!-- Welcome Message -->
                <div class="welcome-message">
                    Consulta y gestiona las calificaciones de tus estudiantes. 
                </div>

                <!-- Search and Filters -->
                <div class="form-group" style="margin-bottom: 2rem;">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" id="buscador" placeholder="Buscar por estudiante, materia o nota..." 
                               onkeyup="buscar()" class="search-input">
                    </div>
                </div>

                <!-- Tabla de Calificaciones -->
                <div class="card">
                    <div class="table-container">
                        <table class="admin-table" id="tabla-calificaciones">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Estudiante</th>
                                    <th>Materia</th>
                                    <th>Nota</th>
                                    <th>Tipo Evaluación</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($calificaciones) > 0): ?>
                                    <?php foreach ($calificaciones as $c): 
                                        $estado = '';
                                        $badge_class = '';
                                        $nota = $c->getNota();
                                        
                                        if ($nota >= 9) {
                                            $estado = 'Excelente';
                                            $badge_class = 'badge-success';
                                        } elseif ($nota >= 7) {
                                            $estado = 'Aprobado';
                                            $badge_class = 'badge-success';
                                        } elseif ($nota >= 6) {
                                            $estado = 'Regular';
                                            $badge_class = 'badge-warning';
                                        } else {
                                            $estado = 'Reprobado';
                                            $badge_class = 'badge-danger';
                                        }
                                        
                                        // Obtener nombres usando los IDs
                                        $estudiante_nombre = $nombres_estudiantes[$c->getEstudianteId()] ?? "Estudiante #" . $c->getEstudianteId();
                                        $materia_nombre = $nombres_materias[$c->getMateriaId()] ?? "Materia #" . $c->getMateriaId();
                                        $tipo_evaluacion_nombre = $nombres_tipos_evaluacion[$c->getTipoEvaluacionId()] ?? "Tipo #" . $c->getTipoEvaluacionId();
                                    ?>
                                        <tr>
                                            <td><?= $c->getId() ?></td>
                                            <td><?= htmlspecialchars($estudiante_nombre) ?></td>
                                            <td><?= htmlspecialchars($materia_nombre) ?></td>
                                            <td>
                                                <strong><?= number_format($nota, 2) ?></strong>/10
                                                <span class="badge <?= $badge_class ?>" style="margin-left: 0.5rem;">
                                                    <?= $estado ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($tipo_evaluacion_nombre) ?></td>
                                            <td><?= $c->getFecha() ?></td>
                                            <td>
                                                <div class="actions-cell">
                                                    <button class="action-btn edit-btn" onclick="editarCalificacion(<?= $c->getId() ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="action-btn delete-btn" onclick="eliminarCalificacion(<?= $c->getId() ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-clipboard-list"></i>
                                                <h3>No hay calificaciones registradas</h3>
                                                <p>Comienza agregando nuevas calificaciones al sistema</p>
                                                <a href="./agregar_calificacion.php" class="btn btn-primary" style="margin-top: 1rem;">
                                                    <i class="fas fa-plus"></i> Agregar Primera Calificación
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
        // Menú móvil
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');
        const userInfo = document.getElementById('userInfo');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuBtn.classList.toggle('active');
            navLinks.classList.toggle('active');
            userInfo.classList.toggle('active');
        });

        // Buscador en vivo
        function buscar() {
            let input = document.getElementById("buscador").value.toLowerCase();
            let filas = document.querySelectorAll("#tabla-calificaciones tbody tr");
            
            filas.forEach(fila => {
                if (fila.querySelector('.empty-state')) {
                    return;
                }
                
                let textoFila = fila.innerText.toLowerCase();
                fila.style.display = textoFila.includes(input) ? "" : "none";
            });
        }

        function editarCalificacion(id) {
            if (confirm('¿Editar esta calificación?')) {
                window.location.href = 'editar_calificacion.php?id=' + id;
            }
        }

        function eliminarCalificacion(id) {
            if (confirm('¿Estás seguro de eliminar esta calificación?')) {
                window.location.href = 'eliminar_calificacion.php?id=' + id;
            }
        }

        // Efecto de scroll en navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.header');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Cerrar menú al hacer clic en un enlace (en móvil)
        document.querySelectorAll('.nav-list a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuBtn.classList.remove('active');
                navLinks.classList.remove('active');
                userInfo.classList.remove('active');
            });
        });
    </script>
</body>
</html>