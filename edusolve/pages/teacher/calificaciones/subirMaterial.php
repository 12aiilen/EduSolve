<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// 🧩 Verificar sesión activa y rol
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../../assets/clases/Usuario.php';

// 🧩 Recuperar objeto profesor
$profesor = unserialize($_SESSION['usuario']);
$profesor_id = $profesor->getIdUsuarios();
$nombreProfesor = $profesor->getNombre() . ' ' . $profesor->getApellido();

// 🧩 Validar rol (4 = profesor)
if ($profesor->getIdTiposUsuarios() != 4) {
    die("Acceso denegado: No tienes permisos de profesor.");
}

// 🔹 Cargar DAL
require_once __DIR__ . '/../../../assets/dal/MateriaDAL.php';
require_once __DIR__ . '/../../../assets/config/AbstractMapper.php';

// 🔹 Obtener materias asignadas desde DAL
require_once __DIR__ . '/../../../assets/dal/AsignacionProfesoresDAL.php';
require_once __DIR__ . '/../../../assets/clases/Materia.php';

// Instanciar DAL
$asignacionDAL = new AsignacionProfesoresDAL();
$materiaDAL = new MateriaDAL();

// Obtener IDs de materias asignadas
$idsMaterias = $asignacionDAL->obtenerMateriasPorProfesor($profesor_id);

// Obtener objetos Materia completos
$materias = [];
if (!empty($idsMaterias)) {
    $materias = $materiaDAL->getByIds($idsMaterias);
}

// 🔹 Inicializar mensajes
$mensaje = '';
$tipoMensaje = '';

// 🔹 Carpeta destino (asegurate de que exista)
$uploadDir = __DIR__ . "/../uploads/material/"; 

// 🔹 Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $materia_id = intval($_POST['materia_id'] ?? 0);
    $archivo = $_FILES['archivo'] ?? null;

    if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = basename($archivo['name']);
        $rutaDestinoFisica = $uploadDir . $nombreArchivo;

        // Mover archivo subido
        if (move_uploaded_file($archivo['tmp_name'], $rutaDestinoFisica)) {
            // Guardar registro en BD
            require_once __DIR__ . '/../../../assets/dal/MaterialDidacticoDAL.php';

            $materialDAL = new MaterialDidacticoDAL();
            $resultado = $materialDAL->insertarMaterial([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'nombre_archivo' => $nombreArchivo,
                'ruta_archivo' => $rutaDestinoFisica,
                'profesor_id' => $profesor_id,
                'materia_id' => $materia_id
            ]);

            if ($resultado) {
                $mensaje = "Material '{$titulo}' subido y guardado con éxito.";
                $tipoMensaje = "success";
            } else {
                unlink($rutaDestinoFisica);
                $mensaje = "Error al registrar material en la base de datos.";
                $tipoMensaje = "danger";
            }
        } else {
            $mensaje = "Fallo al mover el archivo al servidor. Verifica permisos de carpeta.";
            $tipoMensaje = "danger";
        }
    } else {
        $mensaje = "Por favor selecciona un archivo válido.";
        $tipoMensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Material - EduSolve</title>
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
        
        input[type="file"].form-control {
            padding: 0.5rem;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        .form-text {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-light, #7f8c8d);
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
        <div class="container">
            <div class="dashboard-grid">
                <div class="card">
                    <h1 class="admin-title">Subir Material Didáctico</h1>
                    
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-<?= $tipoMensaje === 'success' ? 'success' : 'danger' ?>">
                            <i class="fas fa-<?= $tipoMensaje === 'success' ? 'check' : 'exclamation' ?>-circle"></i>
                            <?= htmlspecialchars($mensaje) ?>
                        </div>
                    <?php endif; ?>

                    <div class="admin-form">
                        <form method="POST" action="" enctype="multipart/form-data">
                            
                            <div class="form-group">
                                <label class="form-label">Título del Material *</label>
                                <input type="text" name="titulo" class="form-control" 
                                       placeholder="Ej: Guía de Ejercicios - Unidad 3" 
                                       value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>" required>
                            </div>

<div class="form-group">
    <label class="form-label">Materia Asociada *</label>
<select name="materia_id" class="form-control" required>
    <option value="">Seleccione una materia</option>
    <?php if (!empty($materias)): ?>
        <?php foreach ($materias as $m): ?>
            <option value="<?= $m->getId() ?>" <?= (($_POST['materia_id'] ?? '') == $m->getId()) ? 'selected' : '' ?>>
                <?= htmlspecialchars($m->getNombre()) ?>
            </option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="" disabled>No tienes materias asignadas</option>
    <?php endif; ?>
</select>

    <?php if (empty($materias)): ?>
        <small class="form-text text-danger">No tienes materias asignadas. Contacta al administrador.</small>
    <?php endif; ?>
</div>

                            <div class="form-group">
                                <label class="form-label">Seleccionar Archivo *</label>
                                <input type="file" name="archivo" class="form-control" required>
                                <small class="form-text">Formatos permitidos: PDF, DOCX, PPTX, imágenes, etc.</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Descripción (opcional)</label>
                                <textarea name="descripcion" class="form-control" rows="3" 
                                          placeholder="Agregue una descripción del material..."><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Subir Material</button>
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
    </script>
</body>
</html>