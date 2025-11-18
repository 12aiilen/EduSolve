<?php
// Carpeta: view/calificaciones/ver_clases.php
session_start();
require_once __DIR__ . '/../../../assets/config/AbstractMapper.php';

// Verificar que haya sesión activa y sea un profesor
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../auth/login.php');
    exit;
}

// Cargar clases necesarias
require_once __DIR__ . '/../../../assets/clases/Usuario.php';

// Recuperar objeto profesor de la sesión
$profesor = unserialize($_SESSION['usuario']);
$profesor_id = $profesor->getIdUsuarios();
$nombreProfesor = $profesor->getNombre() . ' ' . $profesor->getApellido();

// Verificar que sea un profesor
if ($profesor->getIdTiposUsuarios() != 4) { // 4 = profesor en tu base de datos
    die("Acceso denegado: No tienes permisos de profesor.");
}

// Clase para manejar las clases agendadas
class ClaseMapper extends AbstractMapper {
    protected function doLoad($columna) { return $columna; }
    
    public function obtenerClasesPorProfesor($profesor_id) {
        $sql = "SELECT ca.*, m.nombre as materia_nombre, c.Año, c.Division 
                FROM clases_agendadas ca 
                JOIN materias m ON ca.materia_id = m.id 
                JOIN cursos c ON ca.curso_id = c.idCursos 
                WHERE ca.profesor_id = $profesor_id 
                ORDER BY ca.fecha DESC, ca.hora_inicio DESC";
        $this->setConsulta($sql);
        return $this->FindAll();
    }
}

$claseMapper = new ClaseMapper();
$clases = $claseMapper->obtenerClasesPorProfesor($profesor_id);

// Procesar acciones (cancelar clase)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $clase_id = $_POST['clase_id'] ?? '';
    
    if ($_POST['accion'] === 'cancelar' && !empty($clase_id)) {
        $sql = "UPDATE clases_agendadas SET estado = 'Cancelada' WHERE id = $clase_id AND profesor_id = $profesor_id";
        $abstractMapper = new class extends AbstractMapper { protected function doLoad($columna) { return $columna; } };
        $abstractMapper->setConsulta($sql);
        $resultado = $abstractMapper->Execute();
        
        if ($resultado !== false) {
            header('Location: ver_clases.php?success=1');
            exit;
        } else {
            $mensaje = "Error al cancelar la clase.";
            $tipoMensaje = "danger";
        }
    }
}

// Mensajes de éxito
if (isset($_GET['success'])) {
    $mensaje = "Clase cancelada exitosamente.";
    $tipoMensaje = "success";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Clases Agendadas - EduSolve</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="/assets/images/escudo.png" type="image/png">
    <style>
        <?php 
        // Incluir el CSS del profesor si existe
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
        
        /* CONTENIDO PRINCIPAL */
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
        
        /* TARJETAS */
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
            font-weight: 600;
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
        
        /* BOTONES */
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
        
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        /* TABLA DE CLASES */
        .table-container {
            overflow-x: auto;
            margin-top: 2rem;
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
        
        /* BADGES DE ESTADO */
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
        
        .badge-secondary {
            background-color: rgba(149, 165, 166, 0.15);
            color: var(--text-light, #7f8c8d);
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
        
        /* FOOTER */
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
            
            th, td {
                padding: 0.75rem 0.5rem;
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

                    .btn.btn-primary{
                background:#334155
            }
    </style>
</head>
<body>    <header class="header" role="banner">
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
        <div class="container">
            <div class="dashboard-grid">
                <div class="card">
                    <h1 class="admin-title">Mis Clases Agendadas</h1>
                    
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-<?= $tipoMensaje === 'success' ? 'success' : 'danger' ?>">
                            <i class="fas fa-<?= $tipoMensaje === 'success' ? 'check' : 'exclamation' ?>-circle"></i>
                            <?= htmlspecialchars($mensaje) ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-actions">
                        <a href="agendarClase.php" class="btn btn-primary">
                            <i class="fas fa-calendar-plus"></i> Agendar Nueva Clase
                        </a>
                        <a href="../dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </a>
                    </div>

                    <div class="table-container">
                        <?php if (count($clases) > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Materia</th>
                                        <th>Curso</th>
                                        <th>Fecha</th>
                                        <th>Horario</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clases as $clase): 
                                        $fecha = DateTime::createFromFormat('Y-m-d', $clase['fecha']);
                                        $hora_inicio = DateTime::createFromFormat('H:i:s', $clase['hora_inicio']);
                                        $hora_fin = DateTime::createFromFormat('H:i:s', $clase['hora_fin']);
                                        
                                        // Determinar badge según estado
                                        $badge_class = '';
                                        switch ($clase['estado']) {
                                            case 'Pendiente':
                                                $badge_class = 'badge-warning';
                                                break;
                                            case 'Completada':
                                                $badge_class = 'badge-success';
                                                break;
                                            case 'Cancelada':
                                                $badge_class = 'badge-danger';
                                                break;
                                            default:
                                                $badge_class = 'badge-secondary';
                                        }
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($clase['materia_nombre']) ?></td>
                                            <td><?= htmlspecialchars($clase['Año']) ?>°<?= htmlspecialchars($clase['Division']) ?></td>
                                            <td><?= $fecha ? $fecha->format('d/m/Y') : $clase['fecha'] ?></td>
                                            <td>
                                                <?= $hora_inicio ? $hora_inicio->format('H:i') : $clase['hora_inicio'] ?> - 
                                                <?= $hora_fin ? $hora_fin->format('H:i') : $clase['hora_fin'] ?>
                                            </td>
                                            <td>
                                                <span class="badge <?= $badge_class ?>">
                                                    <?= htmlspecialchars($clase['estado']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($clase['estado'] === 'Pendiente'): ?>
                                                    <form method="POST" action="" style="display: inline;">
                                                        <input type="hidden" name="clase_id" value="<?= $clase['id'] ?>">
                                                        <input type="hidden" name="accion" value="cancelar">
                                                        <button type="submit" class="btn btn-danger" 
                                                                onclick="return confirm('¿Estás seguro de cancelar esta clase?')">
                                                            <i class="fas fa-times"></i> Cancelar
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-muted">No disponible</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <h3>No hay clases agendadas</h3>
                                <p>Comienza agendando tu primera clase</p>
                                <a href="agendarClase.php" class="btn btn-primary" style="margin-top: 1rem;">
                                    <i class="fas fa-calendar-plus"></i> Agendar Primera Clase
                                </a>
                            </div>
                        <?php endif; ?>
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