<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Plataforma educativa de la E.E.S.T.N°3 - EduSolve">
    <title>EduSolve - Plataforma Educativa</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="icon" href="../edusolve/assets/images/escudo.png" type="image/png">
</head>
<body>
    <header class="header" role="banner">
        <div class="container">
            <div class="menu">
                <div class="logo-container">
                    <img src="../edusolve/assets/images/escudo.png"  class="header-logo">
                    <a href="index.php" class="logo">EduSolve<span class="school-name">E.E.S.T.N°3</span></a>
                </div>

                <button class="mobile-menu-btn" aria-label="Menú móvil" aria-expanded="false">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>

                <nav class="nav" aria-label="Navegación principal">
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link active">Inicio</a></li>
                        <li><a href="pages/general/materias.php" class="nav-link">Materias</a></li>
                        <li><a href="pages/general/about.php" class="nav-link">Nosotros</a></li>
                        <li><a href="pages/auth/login.php" class="nav-link nav-button">Iniciar Sesión</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section class="hero">
            <div class="container">
                <h1 class="hero-title">Bienvenidos a EduSolve</h1>
                <p class="hero-subtitle">La plataforma educativa de la E.E.S.T.N°3</p>
                <div class="hero-actions">
                    <a href="pages/auth/login.php" class="button button-primary">Acceder al Sistema</a>
                    <a href="pages/general/materias.php" class="button button-secondary">Ver Materias</a>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2 class="section-title">Nuestra Plataforma</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📚</div>
                        <h3>Material Educativo</h3>
                        <p>Acceso a recursos y materiales de estudio para todas las materias.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Seguimiento Académico</h3>
                        <p>Consulta tus calificaciones y progreso académico en tiempo real.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">👨‍🏫</div>
                        <h3>Comunicación Directa</h3>
                        <p>Interacción fluida entre estudiantes, profesores y directivos.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <div class="footer-logo">
                        <img src="../edusolve/assets/images/escudo.png" alt="Escudo de la escuela" width="40">
                        <span>EduSolve</span>
                    </div>
                    <p>Plataforma educativa oficial de la E.E.S.T.N°3</p>
                </div>
                <div class="footer-links">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="pages/general/materias.php">Materias</a></li>
                        <li><a href="pages/general/contacto.php">Contacto</a></li>
                        <li><a href="pages/general/informacion.php">Acerca de</a></li>
                        <li><a href="pages/auth/login.html">Acceso</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>