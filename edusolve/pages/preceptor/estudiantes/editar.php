<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../../../assets/BLL/AlumnoBLL.php");

$alumnoBLL = new AlumnoBLL();

// Obtener ID del alumno (obligatorio para editar)
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
if (!$id) {
    header("Location: listado.php");
    exit;
}

// Cargar datos del alumno
$alumnoObj = $alumnoBLL->getAlumnoById($id);
if (!$alumnoObj) {
    header("Location: listado.php");
    exit;
}

// Obtener lista de turnos
$turnos = $alumnoBLL->getTurnos();

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumnoObj->setNombre($_POST['nombre'] ?? '');
    $alumnoObj->setApellido($_POST['apellido'] ?? '');
    $alumnoObj->setDni($_POST['dni'] ?? '');
    $alumnoObj->setDireccion($_POST['direccion'] ?? '');
    $alumnoObj->setIdTurno($_POST['turno'] ?? 0);

    $alumnoBLL->UpdateAlumno($alumnoObj);

    header("Location: listado.php");
    exit;
}

// Datos para el formularioe
$estudiante = [
    "nombre"    => $alumnoObj->getNombre(),
    "apellido"  => $alumnoObj->getApellido(),
    "dni"       => $alumnoObj->getDni(),
    "direccion" => $alumnoObj->getDireccion(),
    "turno"     => $alumnoObj->getIdTurno()
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? "Editar" : "Nuevo"; ?> Estudiante - EduSolve</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../assets/css/preceptor.css">
    <link rel="icon" href="../../../assets/images/escudo.png" type="image/png">
</head>
<body>
<header class="header">
    <div class="container">
        <div class="menu">
            <div class="logo-container">
                <img src="../../../assets/images/escudo.png" alt="Escudo de la escuela" class="header-logo">
                <a href="../../../index.php" class="logo">EduSolve<span class="school-name"><?= $id ? "Editar" : "Nuevo"; ?> Estudiante</span></a>
            </div>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="../homePreceptor.php" class="nav-link">Inicio</a></li>
                    <li><a href="listado.php" class="nav-link">Estudiantes</a></li>
                    <li><a href="../materias/listado.php" class="nav-link">Materias</a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button></li>
            </nav>
        </div>
    </div>
</header>

<main class="preceptor-panel">
    <div class="container">
        <div class="preceptor-header">
            <h1 class="preceptor-title"><?= $id ? "Editar" : "Nuevo"; ?> Estudiante</h1>
            <a href="listado.php" class="button button-secondary">← Volver</a>
        </div>

        <form class="preceptor-form" method="POST">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($estudiante['nombre'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" id="apellido" name="apellido" class="form-control" value="<?= htmlspecialchars($estudiante['apellido'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" id="dni" name="dni" class="form-control" value="<?= htmlspecialchars($estudiante['dni'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" name="direccion" class="form-control" value="<?= htmlspecialchars($estudiante['direccion'] ?? ''); ?>">
            </div>

<div class="form-group">
    <label for="turno" class="form-label">Turno</label>
    <select id="turno" name="turno" class="form-control" required>
        <option value="">-- Seleccione un turno --</option>
        <?php foreach ($turnos as $turno): ?>
            <option value="<?= $turno['id'] ?>" 
                <?= $estudiante['turno'] == $turno['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($turno['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>



            <div class="form-actions">
                <a href="listado.php" class="button button-secondary">Cancelar</a>
                <button type="submit" class="button button-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="../../../assets/images/escudo.png" alt="Escudo de la escuela" width="40">
                <span>EduSolve</span>
            </div>
            <p>Plataforma educativa oficial de la E.E.S.T.N°3</p>
        </div>
        <div class="footer-links">
            <h4>Enlaces Rápidos</h4>
            <ul>
                <li><a href="../../../index.php">Inicio</a></li>
                <li><a href="../../general/materias.php">Materias</a></li>
                <li><a href="../../general/contacto.php">Contacto</a></li>
                <li><a href="../../general/informacion.php">Acerca de</a></li>
                <li><a href="../../auth/login.html">Acceso</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
    </div>
</footer>

<script src="../../../assets/js/directivo.js"></script>
</body>
</html>
