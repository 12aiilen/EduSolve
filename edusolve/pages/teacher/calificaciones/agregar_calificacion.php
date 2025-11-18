<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

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


require_once __DIR__ . '/../../../assets/config/AbstractMapper.php';
require_once __DIR__ . '/../../../assets/controllers/CalificacionController.php';
$calificacionController = new CalificacionController();

// Obtener datos para los dropdowns con manejo de errores
try {
    $estudiantes = $calificacionController->obtenerEstudiantes();
    $materias = $calificacionController->obtenerMateriasAsignadas($profesor_id);
    $profesores = $calificacionController->obtenerProfesores();
    $tiposEvaluacion = $calificacionController->obtenerTiposEvaluacion();
    
    // Verificar que los arrays no estén vacíos
    if (empty($profesores)) {
        $profesores = []; // Inicializar como array vacío si es null
    }
    if (empty($estudiantes)) {
        $estudiantes = [];
    }
    if (empty($materias)) {
        $materias = [];
    }
    if (empty($tiposEvaluacion)) {
        $tiposEvaluacion = [];
    }
    
    // DEBUG: Mostrar estructura de profesores para depuración
    echo "<!-- DEBUG Profesores: ";
    print_r($profesores);
    echo " -->";
    
} catch (Exception $e) {
    // Manejar errores de base de datos
    $error = "Error al cargar los datos: " . $e->getMessage();
    $estudiantes = [];
    $materias = [];
    $profesores = [];
    $tiposEvaluacion = [];
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estudiante_id = $_POST['estudiante_id'] ?? '';
    $materia_id = $_POST['materia_id'] ?? '';
    $profesor_id = $_POST['profesor_id'] ?? '';
    $calificacion = $_POST['calificacion'] ?? '';
    $tipo_evaluacion_id = $_POST['tipo_evaluacion_id'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';

    // Validar que todos los campos requeridos estén presentes
    if (empty($estudiante_id) || empty($materia_id) || empty($profesor_id) || 
        empty($calificacion) || empty($tipo_evaluacion_id) || empty($fecha)) {
        $error = "Todos los campos marcados con * son obligatorios.";
    } else {
        if ($calificacionController->agregarCalificacion(
            $estudiante_id, $materia_id, $profesor_id, $calificacion, 
            $tipo_evaluacion_id, $fecha, $observaciones
        )) {
            header('Location: listado.php?success=1');
            exit;
        } else {
            $error = "Error al agregar la calificación";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Calificación - EduSolve</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <img src="/edusolve/assets/images/escudo.png" class="header-logo" alt="Escudo de la escuela">
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
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .welcome-message {
            font-size: 1.1rem;
            color: var(--text-light, #7f8c8d);
            margin-bottom: 2rem;
            line-height: 1.6;
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
        
        /* DATALIST PERSONALIZADO */
        .datalist-container {
            position: relative;
        }
        
        .datalist-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 6px 6px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .datalist-option {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s ease;
        }
        
        .datalist-option:hover {
            background-color: #f8f9fa;
        }
        
        .datalist-option:last-child {
            border-bottom: none;
        }
        
        .selected-student {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background-color: #e8f4fd;
            border: 1px solid #b3d9f2;
            border-radius: 4px;
            color: var(--primary, #2c3e50);
            font-weight: 500;
            display: none;
        }
        
        /* CHECKBOX GROUP */
        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .checkbox-item input[type="radio"] {
            width: 18px;
            height: 18px;
        }
        
        .checkbox-item label {
            font-weight: 500;
            color: var(--text, #333);
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
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
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
            
            .checkbox-group {
                grid-template-columns: 1fr;
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
            
            .checkbox-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header" role="banner">
        <div class="header-container">
            <div class="menu">
                <div class="logo-container">
                    <img src="../assets/images/escudo.png" class="header-logo" alt="Escudo de la escuela">
                    <a href="dashboard.php" class="logo">
                        <i class="fas fa-graduation-cap"></i>
                        EduSolve<span class="school-name">E.E.S.T.N°3</span>
                    </a>
                </div>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
                
                <nav class="nav" aria-label="Navegación del Profesor">
                    <ul class="nav-list" id="navLinks">
                        <li><a href="dashboard.php"></i> Inicio</a></li>
                        <li><a href="listado.php"></i> Calificaciones</a></li>
                        <li><a href="agendarClase.php"></i> Agendar Clase</a></li>
                        <li><a href="ver_clases.php"></i> Mis Clases</a></li>
                        <li><a href="subirMaterial.php"></i> Subir Material</a></li>
                        <li><a href="agregarCalificacion.php"></i> Agregar Calificación</a></li>
                        <li><a href="../Perfil/Perfil.php" class="user-avatar">A</a></li>
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
                    <h1 class="admin-title">Agregar Calificación</h1>
                    
                    <div class="welcome-message">
                        Complete el formulario para registrar una nueva calificación en el sistema.
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($profesores)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No hay profesores disponibles en el sistema. Contacte al administrador.
                        </div>
                    <?php endif; ?>

                    <div class="admin-form">
                        <form method="POST" action="">
                            <!-- Estudiante -->
                            <div class="form-group">
                                <label class="form-label">Estudiante *</label>
                                <div class="datalist-container">
                                    <input type="text" 
                                           name="estudiante_nombre" 
                                           id="estudianteInput" 
                                           class="form-control" 
                                           placeholder="Buscar estudiante..."
                                           autocomplete="off"
                                           value="<?= htmlspecialchars($_POST['estudiante_nombre'] ?? '') ?>"
                                           required>
                                    <input type="hidden" name="estudiante_id" id="estudianteId" value="<?= htmlspecialchars($_POST['estudiante_id'] ?? '') ?>">
                                    <div class="datalist-options" id="estudiantesOptions"></div>
                                </div>
                                <div class="selected-student" id="selectedStudent">
                                    Estudiante seleccionado
                                </div>
                            </div>

                            <!-- Materia -->
                            <div class="form-group">
                                <label class="form-label">Materia *</label>
                                <select name="materia_id" class="form-control" required>
                                    <option value="">Seleccionar materia</option>
                                    <?php if (!empty($materias)): ?>
                                        <?php foreach ($materias as $materia): ?>
                                            <option value="<?= $materia['id'] ?>" 
                                                <?= (isset($_POST['materia_id']) && $_POST['materia_id'] == $materia['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($materia['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Profesor -->


                            <!-- Tipo de Evaluación -->
                            <div class="form-group">
                                <label class="form-label">Tipo de Evaluación *</label>
                                <div class="checkbox-group">
                                    <?php if (!empty($tiposEvaluacion)): ?>
                                        <?php foreach ($tiposEvaluacion as $tipo): ?>
                                            <div class="checkbox-item">
                                                <input type="radio" 
                                                       name="tipo_evaluacion_id" 
                                                       value="<?= $tipo['id'] ?>" 
                                                       id="tipo_<?= $tipo['id'] ?>"
                                                       <?= (isset($_POST['tipo_evaluacion_id']) && $_POST['tipo_evaluacion_id'] == $tipo['id']) ? 'checked' : '' ?>
                                                       required>
                                                <label for="tipo_<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['nombre']) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            No hay tipos de evaluación disponibles.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Calificación y Fecha -->
                            <div class="form-group row-group">
                                <div class="col">
                                    <label class="form-label">Calificación (0-10) *</label>
                                    <input type="number" 
                                           name="calificacion" 
                                           class="form-control" 
                                           step="0.01" 
                                           min="0" 
                                           max="10" 
                                           value="<?= htmlspecialchars($_POST['calificacion'] ?? '') ?>"
                                           required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Fecha *</label>
                                    <input type="date" 
                                           name="fecha" 
                                           class="form-control" 
                                           value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>"
                                           required>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="form-group">
                                <label class="form-label">Observaciones (opcional)</label>
                                <textarea name="observaciones" class="form-control" rows="3" placeholder="Agregue observaciones adicionales..."><?= htmlspecialchars($_POST['observaciones'] ?? '') ?></textarea>
                            </div>

                            <!-- Botones -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Calificación
                                </button>
                                <a href="listado.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Datos de estudiantes para el buscador
            const estudiantes = <?= !empty($estudiantes) ? json_encode($estudiantes) : '[]' ?>;
            const estudianteInput = document.getElementById('estudianteInput');
            const estudianteId = document.getElementById('estudianteId');
            const estudiantesOptions = document.getElementById('estudiantesOptions');
            const selectedStudent = document.getElementById('selectedStudent');

            // Función para filtrar estudiantes
            function filtrarEstudiantes(termino) {
                return estudiantes.filter(est => {
                    const apellido = est.apellido || est.Apellido || '';
                    const nombre = est.nombre || est.Nombre || '';
                    const nombreCompleto = `${apellido}, ${nombre}`;
                    
                    return apellido.toLowerCase().includes(termino.toLowerCase()) ||
                           nombre.toLowerCase().includes(termino.toLowerCase()) ||
                           nombreCompleto.toLowerCase().includes(termino.toLowerCase());
                });
            }

            // Mostrar opciones al enfocar el input
            estudianteInput.addEventListener('focus', function() {
                mostrarOpciones(filtrarEstudiantes(''));
            });

            // Filtrar opciones al escribir
            estudianteInput.addEventListener('input', function() {
                const termino = this.value;
                mostrarOpciones(filtrarEstudiantes(termino));
                
                // Si el input está vacío, limpiar el ID seleccionado
                if (termino === '') {
                    estudianteId.value = '';
                    selectedStudent.style.display = 'none';
                }
            });

            // Mostrar opciones en el datalist
            function mostrarOpciones(estudiantesFiltrados) {
                estudiantesOptions.innerHTML = '';
                
                if (estudiantesFiltrados.length > 0) {
                    estudiantesFiltrados.forEach(est => {
                        const option = document.createElement('div');
                        option.className = 'datalist-option';
                        const apellido = est.apellido || est.Apellido || '';
                        const nombre = est.nombre || est.Nombre || '';
                        option.textContent = `${apellido}, ${nombre}`;
                        option.setAttribute('data-id', est.idUsuarios || est.id);
                        
                        option.addEventListener('click', function() {
                            estudianteInput.value = this.textContent;
                            estudianteId.value = this.getAttribute('data-id');
                            estudiantesOptions.style.display = 'none';
                            selectedStudent.textContent = `Estudiante seleccionado: ${this.textContent}`;
                            selectedStudent.style.display = 'block';
                        });
                        
                        estudiantesOptions.appendChild(option);
                    });
                    estudiantesOptions.style.display = 'block';
                } else {
                    estudiantesOptions.style.display = 'none';
                }
            }

            // Ocultar opciones al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.datalist-container')) {
                    estudiantesOptions.style.display = 'none';
                }
            });

            // Validación del formulario
            document.querySelector('form').addEventListener('submit', function(e) {
                if (!estudianteId.value) {
                    e.preventDefault();
                    alert('Por favor, seleccione un estudiante válido de la lista.');
                    estudianteInput.focus();
                    return;
                }

                const calificacion = parseFloat(document.querySelector('input[name="calificacion"]').value);
                if (calificacion < 0 || calificacion > 10) {
                    e.preventDefault();
                    alert('La calificación debe estar entre 0 y 10.');
                    return;
                }

                const fecha = document.querySelector('input[name="fecha"]').value;
                if (!fecha) {
                    e.preventDefault();
                    alert('Por favor, seleccione una fecha.');
                    return;
                }
            });

            // Menú móvil - IGUAL QUE EN DASHBOARD
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const navLinks = document.getElementById('navLinks');
            const userInfo = document.getElementById('userInfo');

            mobileMenuBtn.addEventListener('click', () => {
                mobileMenuBtn.classList.toggle('active');
                navLinks.classList.toggle('active');
                userInfo.classList.toggle('active');
            });

            // Efecto de scroll en navbar - IGUAL QUE EN DASHBOARD
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.header');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Cerrar menú al hacer clic en un enlace (en móvil) - IGUAL QUE EN DASHBOARD
            document.querySelectorAll('.nav-list a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenuBtn.classList.remove('active');
                    navLinks.classList.remove('active');
                    userInfo.classList.remove('active');
                });
            });
        });
    </script>
</body>
</html>