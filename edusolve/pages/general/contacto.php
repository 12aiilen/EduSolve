<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" href="../../../edusolve/assets/images/escudo.png" type="image/png">
    <title>Contacto - EduSolve</title>
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
            background-color: #ecf0f1;
            border-radius: 50%;
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
        
        /* Sección de contacto */
        .contact-section h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 36px;
            color: var(--primary-color);
        }
        
        .contact-section > p {
            text-align: center;
            margin-bottom: 40px;
            color: #555;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        
        .contact-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .contact-info {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .button:hover {
            background-color: #1a252f;
        }
        
        .contact-details li {
            margin-bottom: 20px;
            list-style: none;
        }
        
        .contact-details strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 5px;
        }
        
        .map-container {
            margin-top: 30px;
        }
        
        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: #ecf0f1;
            padding: 40px 0 20px;
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
            
            .contact-grid {
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
                     <img src="../../../edusolve/assets/images/escudo.png" alt="Escudo de la Escuela" style="height:40px; width:auto;">
                    <a href="../../index.html" class="logo">EduSolve<span class="school-name">E.E.S.T.N°3</span></a>
                </div>

                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="../../index.php" class="nav-link">Inicio</a></li>
                        <li><a href="materias.php" class="nav-link">Materias</a></li>
                        <li><a href="informacion.php" class="nav-link">Sobre Nosotros</a></li>
                        <li><a href="contacto.php" class="nav-link active">Contacto</a></li>
                        <li><a href="../auth/login.php" class="nav-link nav-button">Iniciar Sesión</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <section class="contact-section">
                <h1>Contacto</h1>
                <p>¿Tienes alguna pregunta o comentario? Contáctanos a través del siguiente formulario.</p>
                
                <div class="contact-grid">
                    <div class="contact-form">
                        <form id="contactForm">
                            <div class="form-group">
                                <label for="name">Nombre:</label>
                                <input type="text" id="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Correo Electrónico:</label>
                                <input type="email" id="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Asunto:</label>
                                <select id="subject" required>
                                    <option value="">Seleccione un asunto</option>
                                    <option value="info">Información general</option>
                                    <option value="support">Soporte técnico</option>
                                    <option value="admission">Admisión</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Mensaje:</label>
                                <textarea id="message" rows="5" required></textarea>
                            </div>
                            
                            <button type="submit" class="button">Enviar Mensaje</button>
                        </form>
                    </div>
                    
                    <div class="contact-info">
                        <h3>Información de Contacto</h3>
                        <ul class="contact-details">
                            <li>
                                <strong>Dirección:</strong>
                                <p>Juan XXIII 125, Maquinista Savio, Provincia de Buenos Aires</p>
                            </li>
                            <li>
                                <strong>Teléfono:</strong>
                                <p>(123) 456-7890</p>
                            </li>
                            <li>
                                <strong>Email:</strong>
                                <p>contacto@edusolve.edu</p>
                            </li>
                            <li>
                                <strong>Horario de atención:</strong>
                                <p>Lunes a Viernes: 7:15 a 22</p>
                            </li>
                        </ul>
                        
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d727.5530277864132!2d-58.77396844611171!3d-34.409771272160356!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bc9f0789854cc7%3A0xbfabee3cd15e0d3b!2sEscuela%20de%20Educaci%C3%B3n%20Secundaria%20T%C3%A9cnica%20N%C2%BA3%20%22Eva%20Per%C3%B3n%22!5e1!3m2!1ses-419!2sar!4v1759277025518!5m2!1ses-419!2sar " 
                                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <div class="footer-logo">
                        <!-- <div style="width:40px; height:40px; background-color:#ecf0f1; border-radius:50%;"></div> -->
                         <img src="../../../edusolve/assets/images/escudo.png" alt="Escudo de la Escuela" style="height:40px; width:auto;">
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
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Gracias por tu mensaje. Nos pondremos en contacto contigo pronto.');
            this.reset();
        });
    </script>
</body>
</html>