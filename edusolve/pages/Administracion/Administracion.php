<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../assets/clases/Usuario.php';
require_once __DIR__ . '/../../assets/dal/UsuariosDAL.php';


// Verifica que haya sesión activa
if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit();
}

// Recupera el objeto usuario
$usuario = unserialize($_SESSION["usuario"]);
$idTipo = (int)$usuario->getIdTiposUsuarios();

// Solo los tipo 3 (administradores) pueden acceder
if ($idTipo !== 3) {
    header("Location: ../auth/login.php");
    exit();
}

// Rutas correctas a tus clases
require_once('../../assets/dal/administracionDAL.php');
require_once('../../assets/clases/Admin.php');


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Sistema de Gestión Escolar</title>
    <link rel="icon" href="../../../edusolve/assets/images/escudo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../edusolve/assets/css/main.css">
     <!-- /*  esto hace que choquen los css si esta esto anda el footer y no el nav ===== */ -->
    <style>
        /* ===== RESET Y VARIABLES ===== */
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --text: #333;
            --text-light: #7f8c8d;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            line-height: 1.6;
            color: var(--text);
            background-color: #f9f9f9;
        }
        
        /* ===== TIPOGRAFÍA ===== */
        h1, h2, h3, h4 {
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        
        p {
            margin-bottom: 1rem;
        }
        
        /* ===== LAYOUT ===== */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* ===== HEADER ===== */
        .navbar {
            background-color: var(--primary);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .navbar.scrolled {
            padding: 0.5rem 0;
        }
        
        .menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .logo {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        
        .logo:hover {
            color: var(--secondary);
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 1.5rem;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
            transition: var(--transition);
        }
        
        .nav-links a:hover {
            color: var(--secondary);
        }
        
        .nav-links a.active {
            color: var(--secondary);
            font-weight: 600;
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--secondary);
            bottom: 0;
            left: 0;
            transition: var(--transition);
        }
        
        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }
        
    .user-info {
    margin-left: 20px;   /* antes seguro tenías 20px o más */
    width: 100%;
    align-items: flex-start;
}

.user-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #4a90e2; /* color de fondo */
    color: #fff;
    font-weight: bold;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    text-decoration: none;
    transition: 0.3s;
}

.user-avatar:hover {
    background: #357ab8;
}
        
        /* ===== BANNER ===== */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary) 0%, var(--dark) 100%);
            color: white;
            padding: 3rem 2rem;
            border-radius: 8px;
            margin: 2rem auto;
            box-shadow: var(--shadow);
            text-align: center;
        }
        
        .title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        /* ===== GRID Y CARDS ===== */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem auto;
        }
        
        .card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            height: 100%;
            border-top: 4px solid var(--secondary);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .card h2 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .card h2 i {
            color: var(--secondary);
        }
        
        .card p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }
        
        /* ===== BOTONES ===== */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            text-align: center;
            cursor: pointer;
            background-color: var(--secondary);
            color: white;
            border: none;
            width: 100%;
        }
        
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        /* ===== FOOTER ===== */
        .footer {
            background-color: var(--primary);
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }
            
            .menu {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .nav-links {
                margin-top: 1rem;
                flex-direction: column;
                width: 100%;
                gap: 1rem;
            }
            
            .user-info {
                margin-top: 1rem;
                width: 100%;
                justify-content: flex-start;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .title {
                font-size: 2rem;
            }
            
            .welcome-banner {
                padding: 2rem 1rem;
            }
        }
        
        /* ===== MENÚ MÓVIL ===== */
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            background: transparent;
            border: none;
            cursor: pointer;
            gap: 4px;
            padding: 0.5rem;
        }
        
        .bar {
            width: 25px;
            height: 3px;
            background-color: white;
            transition: var(--transition);
        }
        
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
                position: absolute;
                right: 1.5rem;
                top: 1.5rem;
            }
            
            .nav-links {
                display: none;
                width: 100%;
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .user-info {
                display: none;
            }
            
            .user-info.active {
                display: flex;
            }
            
            .mobile-menu-btn.active .bar:nth-child(1) {
                transform: rotate(45deg) translate(5px, 5px);
            }
            
            .mobile-menu-btn.active .bar:nth-child(2) {
                opacity: 0;
            }
            
            .mobile-menu-btn.active .bar:nth-child(3) {
                transform: rotate(-45deg) translate(7px, -6px);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="navbar">
        <div class="menu">
            <a href="admins.php" class="logo">
            <img src="../../../edusolve/assets/images/escudo.png" alt="Escudo de la Escuela" style="height:40px; width:auto;">
                    Sistema de Gestión Escolar</a>

            
            <ul class="nav-links">
                <li><a href="admin.php" class="active">Inicio</a></li>
                <li><a href="../teacher/dashboard.php">Profesores</a></li>
                <li><a href="../student/dashboard.php">Estudiantes</a></li>
                <li><a href="../preceptor/homePreceptor.php">Preceptores</a></li>
                <li><a href="../Administracion/perfil/perfil.php" class="user-avatar">A</a></li>
                <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../auth/logout.php'">Cerrar Sesión</button></li>
            </ul>
            
            
            <button class="mobile-menu-btn">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="container">
        <section class="welcome-banner">
            <h1 class="title">Panel de Administración</h1>
            <p class="subtitle">Bienvenido al Sistema de Gestión Escolar. Desde aquí puedes acceder a todos los módulos del sistema.</p>
        </section>

        <section class="grid">
            <div class="card">
                <h2><i class="fas fa-chalkboard-teacher"></i> Módulo de Profesores</h2>
                <p>Administra la información de los docentes, sus asignaturas, y horarios. Gestiona el perfil académico de cada profesor.</p>
                <a href="../teacher/dashboard.php" class="btn">Acceder al módulo</a>
            </div>
            
            <div class="card">
                <h2><i class="fas fa-user-graduate"></i> Módulo de Estudiantes</h2>
                <p>Gestiona la información de los estudiantes, inscripciones, calificaciones, asistencia y historial académico de cada alumno.</p>
                <a href="../student/dashboard.php" class="btn">Acceder al módulo</a>
            </div>
            
            <div class="card">
                <h2><i class="fas fa-clipboard-list"></i> Módulo de Preceptores</h2>
                <p>Administra la información de los preceptores,calificaciones   , control de asistencia, disciplina y seguimiento del comportamiento estudiantil.</p>
                <a href="../preceptor/homePreceptor.php" class="btn">Acceder al módulo</a>
            </div>
        </section>
    </main>

    <!-- Footer -->

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
                        <li><a href="../teacher/dashboard.php">Profesores</a></li>
                        <li><a href="../student/dashboard.php">Estudiantes</a></li>
                        <li><a href="../preceptor/homePreceptor.php">Preceptores</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>


    <script>
        // Menú móvil
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            const userInfo = document.querySelector('.user-info');
            
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenuBtn.classList.toggle('active');
                navLinks.classList.toggle('active');
                userInfo.classList.toggle('active');
            });
            
            // Efecto de scroll en navbar
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        });
    </script>
</body>
</html>