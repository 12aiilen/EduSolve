<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../../../assets/clases/Usuario.php';
require_once __DIR__ . '/../../../assets/BLL/AlumnoBLL.php';

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../auth/login.php");
    exit();
}

$usuario = unserialize($_SESSION["usuario"]);
$idPreceptor = $usuario->getIdUsuarios(); // ID del preceptor logueado

$alumnoBLL = new AlumnoBLL();
$alumnos = $alumnoBLL->obtenerEstudiantesPorPreceptor($idPreceptor); // ✅ ahora solo sus alumnos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Alumnos - EduSolve</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../assets/css/preceptor.css">
    <link rel="icon" href="../../../assets/images/escudo.png" type="image/png">
</head>

<body>
<header class="header">
    <div class="container">
        <div class="logo-container">
            <img src="../../../assets/images/escudo.png" alt="Escudo de la escuela" class="header-logo">
            <a href="../../../index.php" class="logo">EduSolve</a>
        </div>
        <nav class="nav">
            <ul class="nav-list">
                <li><a href="../homePreceptor.php" class="nav-link">Inicio</a></li>
                <li><a href="#" class="nav-link active">Estudiantes</a></li>
                <li><a href="../materias/listado.php" class="nav-link">Materias</a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button></li>
            </ul>
        </nav>
    </div>
</header>

<?php if (isset($_GET['msg'])): ?>
    <?php 
        $msg = $_GET['msg'];
        $id = $_GET['id'] ?? '';
    ?>
    <div id="alerta-msg" 
         style="
            transition: opacity 0.5s ease;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 500;
            <?php if ($msg === 'ok'): ?>
                background-color:#d4edda;
                color:#155724;
                border:1px solid #c3e6cb;
            <?php else: ?>
                background-color:#f8d7da;
                color:#721c24;
                border:1px solid #f5c6cb;
            <?php endif; ?>
        ">
        <?php if ($msg === 'ok'): ?>
            ✅ Alumno registrado correctamente <?= $id ? "con ID: $id" : "" ?>.
        <?php else: ?>
            ❌ Error al registrar el alumno. Inténtelo nuevamente.
        <?php endif; ?>
    </div>

    <script>
        // Ocultar el mensaje después de 4 segundos
        setTimeout(() => {
            const alerta = document.getElementById('alerta-msg');
            if (alerta) {
                alerta.style.opacity = '0';
                setTimeout(() => alerta.remove(), 500); // Espera a que termine la animación
            }
        }, 4000);
    </script>
<?php endif; ?>


<main class="preceptor-panel">
    <div class="container">
        <div class="preceptor-header">
            <h1 class="preceptor-title">Listado de Alumnos</h1>
            <div class="preceptor-actions">
                <a href="cargarNuevo.php" class="button button-primary">+ Nuevo Alumno</a>
                <a href="../homePreceptor.php" class="button button-secondary">← Volver</a>
            </div>
        </div>

        <!-- Barra de búsqueda simple -->
        <div class="preceptor-search">
            <input type="text" id="searchInput" placeholder="Buscar alumno..." class="form-control">
        </div>

<table class="preceptor-table" id="alumnosTable">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Curso</th>
            <th>Dirección</th>
            <th>Turno</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($alumnos as $alumno): ?>
            <tr>
                <td><?= htmlspecialchars($alumno->getNombre() ?? '') ?></td>
                <td><?= htmlspecialchars($alumno->getApellido() ?? '') ?></td>
                <td><?= htmlspecialchars($alumno->getDni() ?? '') ?></td>
                <td><?= htmlspecialchars($alumno->getIdCursos() ?? '') ?></td>
                <td><?= htmlspecialchars($alumno->getDireccion() ?? '') ?></td>
                <td><?= htmlspecialchars($alumno->getTurnoNombre() ?? '') ?></td>
                <td>
                    <a href="editar.php?id=<?= $alumno->getId() ?>" class="button button-primary">Editar</a>
                    <form method="POST" action="../../../assets/controllers/deleteAlumno.control.php" style="display:inline;">
                        <input type="hidden" name="idAlumno" value="<?= $alumno->getId() ?>">
                        <button type="submit" class="button button-secondary"
                            onclick="return confirm('¿Estás seguro de eliminar este alumno?');">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    </div>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
    </div>
</footer>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#alumnosTable tbody tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let match = false;
        cells.forEach(cell => {
            if (cell.textContent.toLowerCase().includes(filter)) {
                match = true;
            }
        });
        row.style.display = match ? '' : 'none';
    });
});
</script>
</body>
</html>
