<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../edusolve/assets/images/escudo.png" type="image/png">
    <title>Materias - EduSolve</title>
    <style>
        :root {
            --primary-color: #2c3e50;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        .header {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .header-logo {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .school-name {
            font-size: 14px;
            color: #ecf0f1;
            margin-left: 5px;
        }
        
        .nav-list {
            display: flex;
            list-style: none;
        }
        
        .nav-link {
            text-decoration: none;
            color: #ecf0f1;
            padding: 10px 15px;
            transition: all 0.3s;
            position: relative;
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: all 0.3s;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        
        .nav-button {
            background-color: #3498db;
            color: white !important;
            border-radius: 5px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }
        
        .nav-button:hover {
            background-color: #2980b9;
        }
        
        /* Main content */
        .main-content {
            padding: 40px 0;
        }
        
        .materias-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .materias-title {
            font-size: 36px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .materias-subtitle {
            color: #555;
            font-size: 18px;
        }
        
        /* Filtros */
        .materias-filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 40px;
        }
        
        .filter-button {
            padding: 10px 20px;
            border: 2px solid var(--primary-color);
            background: white;
            color: var(--primary-color);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .filter-button:hover, .filter-button.active {
            background: var(--primary-color);
            color: white;
        }
        
        /* Grid de materias */
        .materias-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .materia-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .materia-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .materia-image {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: bold;
        }
        
        .materia-content {
            padding: 20px;
        }
        
        .materia-name {
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 20px;
        }
        
        .materia-teacher {
            color: #7f8c8d;
            margin-bottom: 10px;
            font-style: italic;
        }
        
        .materia-description {
            margin-bottom: 15px;
            color: #555;
        }
        
        .materia-stats {
            display: flex;
            justify-content: space-between;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: #ecf0f1;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        
        .footer-info {
            flex: 1;
            min-width: 300px;
            margin-bottom: 20px;
        }
        
         .footer-logo {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        } 
        
        .footer-logo img {
    margin-right: 10px;
    /* border-radius: 50%; */
}

        
        .footer-links h4 {
            margin-bottom: 15px;
            color: #3498db;
        }
        
        .footer-links ul {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 8px;
        }
        
        .footer-links a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #3498db;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #bdc3c7;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .menu {
                flex-direction: column;
            }
            
            .nav-list {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .materias-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="menu">
                <div class="logo-container">
                    <div class="header-logo">
                         <img src="../../../edusolve/assets/images/escudo.png" alt="Escudo de la escuela" class="header-logo">
                        
                    </div>
                    <a href="../../index.html" class="logo">EduSolve<span class="school-name">E.E.S.T.N°3</span></a>
                </div>

                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="../../index.php" class="nav-link">Inicio</a></li>
                        <li><a href="materias.php" class="nav-link active">Materias</a></li>
                        <li><a href="contacto.php" class="nav-link">Contacto</a></li>
                        <li><a href="informacion.php" class="nav-link">Sobre nosotros</a></li>
                        <li><a href="../auth/login.html" class="nav-link nav-button">Iniciar Sesión</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <section class="materias-header">
                <h1 class="materias-title">Nuestras Materias</h1>
                <p class="materias-subtitle">Conoce el plan de estudios de nuestra institución</p>
            </section>
            
            <div class="materias-filters">
                <button class="filter-button active" data-filter="all">Todas</button>
                <button class="filter-button" data-filter="1">1° Año</button>
                <button class="filter-button" data-filter="2">2° Año</button>
                <button class="filter-button" data-filter="3">3° Año</button>
                <button class="filter-button" data-filter="4">4° Año</button>
            </div>
            
            <div class="materias-grid">
                <article class="materia-card" data-year="1">
                    <div class="materia-image" style="background-color: #3498db;">
                        <span>M</span>
                    </div>
                    <div class="materia-content">
                        <h3 class="materia-name">Matemáticas</h3>
                        <p class="materia-teacher">Prof. García</p>
                        <p class="materia-description">Álgebra, geometría y conceptos matemáticos fundamentales.</p>
                        <div class="materia-stats">
                            <span>1° Año</span>
                            <span>4 horas semanales</span>
                        </div>
                    </div>
                </article>
                
                <article class="materia-card" data-year="1">
                    <div class="materia-image" style="background-color: #e74c3c;">
                        <span>L</span>
                    </div>
                    <div class="materia-content">
                        <h3 class="materia-name">Lengua y Literatura</h3>
                        <p class="materia-teacher">Prof. Martínez</p>
                        <p class="materia-description">Análisis de textos, gramática y producción escrita.</p>
                        <div class="materia-stats">
                            <span>1° Año</span>
                            <span>3 horas semanales</span>
                        </div>
                    </div>
                </article>
                
                <article class="materia-card" data-year="2">
                    <div class="materia-image" style="background-color: #2ecc71;">
                        <span>F</span>
                    </div>
                    <div class="materia-content">
                        <h3 class="materia-name">Física</h3>
                        <p class="materia-teacher">Prof. López</p>
                        <p class="materia-description">Principios fundamentales de mecánica y energía.</p>
                        <div class="materia-stats">
                            <span>2° Año</span>
                            <span>3 horas semanales</span>
                        </div>
                    </div>
                </article>
                
                <article class="materia-card" data-year="3">
                    <div class="materia-image" style="background-color: #f39c12;">
                        <span>P</span>
                    </div>
                    <div class="materia-content">
                        <h3 class="materia-name">Programación</h3>
                        <p class="materia-teacher">Prof. Rodríguez</p>
                        <p class="materia-description">Fundamentos de programación y desarrollo de software.</p>
                        <div class="materia-stats">
                            <span>3° Año</span>
                            <span>5 horas semanales</span>
                        </div>
                    </div>
                </article>


                                <article class="materia-card" data-year="4">
                    <div class="materia-image" style="background-color: #3498db;">
                        <span>M</span>
                    </div>
                    <div class="materia-content">
                        <h3 class="materia-name">Matemáticas</h3>
                        <p class="materia-teacher">Prof. García</p>
                        <p class="materia-description">Álgebra, geometría y conceptos matemáticos fundamentales.</p>
                        <div class="materia-stats">
                            <span>4° Año</span>
                            <span>4 horas semanales</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <div class="footer-logo">
                        <!-- <div style="width:40px; height:40px; background-color:#ecf0f1; border-radius:50%;"></div> -->
                          <img src="../../../edusolve/assets/images/escudo.png" alt="Escudo de la escuela" class="header-logo">
                        <span>EduSolve</span>
                    </div>
                    <p>Plataforma educativa oficial de la E.E.S.T.N°3</p>
                </div>
                <div class="footer-links">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="../../index.php">Inicio</a></li>
                        <li><a href="materias.php">Materias</a></li>
                        <li><a href="contacto.php">Contacto</a></li>
                        <li><a href="informacion.php">Acerca de</a></li>
                        <li><a href="../auth/login.html">Acceso</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Filtrado de materias por año
        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Actualizar botones activos
                document.querySelectorAll('.filter-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                // Filtrar materias
                document.querySelectorAll('.materia-card').forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-year') === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>