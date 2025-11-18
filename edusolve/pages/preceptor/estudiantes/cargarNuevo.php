<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../../assets/DAL/TutorDAL.php';
require_once __DIR__ . '/../../../assets/DAL/CursoDAL.php';
require_once __DIR__ . '/../../../assets/DAL/AlumnoDAL.php';
require_once __DIR__ . '/../../../assets/clases/Alumno.php';
require_once __DIR__ . '/../../../assets/DAL/TurnoDAL.php';
require_once __DIR__ . '/../../../assets/clases/Turno.php';

// Instanciar DALs
$tutorDAL = new TutorDAL();
$alumnoDAL = new AlumnoDAL();
$cursoDAL = new CursoDAL();
$turnoDAL = new TurnoDAL();

// Obtener todos los tutores
$tutores = $tutorDAL->findAllTutor();

// Obtener todos los turnos
$turnos = $turnoDAL->findAllTurnos();

// Verificar sesión del preceptor
$idPreceptor = $_SESSION['idPreceptor'] ?? 0;
if ($idPreceptor <= 0) {
    echo "⚠️ No hay un preceptor identificado en sesión.";
    exit;
}

// Traer curso asociado al preceptor
$curso = $cursoDAL->getCursoPorPreceptor($idPreceptor);
if (!$curso) {
    echo "⚠️ No hay un curso asociado al preceptor.";
    exit;
}
$idCurso = (int)$curso['idCursos'];

// Procesar formulario
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['DNI'] ?? '');
    $nombre = trim($_POST['Nombre'] ?? '');
    $apellido = trim($_POST['Apellido'] ?? '');
    $genero = $_POST['Genero'] ?? '';
    $nacionalidad = trim($_POST['Nacionalidad'] ?? '');
    $fechaNacimiento = $_POST['FechaNacimiento'] ?? '';
    $direccion = trim($_POST['Direccion'] ?? '');
    $idTutores = (int)($_POST['idTutores'] ?? 0);
    $idTiposUsuarios = 3; // Siempre alumno
    $idTurno = (int)($_POST['idTurno'] ?? 0);

    if ($dni && $nombre && $apellido && $idTutores > 0 && $idTurno > 0) {
        // Crear objeto Alumno incluyendo el turno
        $alumno = new Alumno(
            0, // idAlumnos
            $dni,
            $nombre,
            $apellido,
            $genero,
            $nacionalidad,
            $fechaNacimiento,
            $direccion,
            $idCurso,
            $idTiposUsuarios,
            $idTutores,
            $idTurno // ✅ Pasar turno al constructor
        );

        $idNuevoAlumno = $alumnoDAL->InsertarAlumno($alumno);

        if ($idNuevoAlumno > 0) {
            header("Location: listado.php");
            exit;
        } else {
            $error = "⚠️ Ocurrió un error al registrar el alumno.";
        }
    } else {
        $error = "⚠️ Debes completar todos los campos obligatorios, incluyendo el turno.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrar Alumno - EduSolve</title>
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
                <li><a href="listado.php" class="nav-link active">Estudiantes</a></li>
                <li><a href="../materias/listado.php" class="nav-link">Materias</a></li>
                <li><button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button></li>
            </ul>
        </nav>
    </div>
</header>

<main class="preceptor-panel">
<div class="container">
    <div class="preceptor-header">
        <h1 class="preceptor-title">Registrar Nuevo Alumno</h1>
        <div class="preceptor-actions">
            <a href="listado.php" class="button button-secondary">← Volver al listado</a>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($tutores)): ?>
        <div class="alert alert-warning">
            ⚠️ No hay tutores registrados. Primero debes registrar al menos un tutor.
        </div>
    <?php else: ?>
        <form action="" method="post" class="preceptor-form">
            <div class="form-group">
                <label>DNI:</label>
                <input type="text" name="DNI" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="Nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="Apellido" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Género:</label>
                <select name="Genero" class="form-control" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nacionalidad:</label>
                <input type="text" name="Nacionalidad" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Fecha de Nacimiento:</label>
                <input type="date" name="FechaNacimiento" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Dirección:</label>
                <input type="text" name="Direccion" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Turno:</label>
                <select name="idTurno" class="form-control" required>
<?php foreach ($turnos as $turno): ?>
    <option value="<?= $turno->getIdTurno() ?>">
        <?= htmlspecialchars($turno->getNombre()) ?>
    </option>
<?php endforeach; ?>

                </select>
            </div>
            <div class="form-group">
                <label>Tutor:</label>
                <select name="idTutores" class="form-control" required>
                    <?php foreach ($tutores as $tutor): ?>
                        <option value="<?= $tutor->getId() ?>"><?= htmlspecialchars($tutor->getNombre() . " " . $tutor->getApellido()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="idCursos" value="<?= htmlspecialchars($idCurso) ?>">
            <input type="hidden" name="idTiposUsuarios" value="3">

            <div class="form-actions">
                <button type="submit" class="button button-primary">Registrar Alumno</button>
                <a href="listado.php" class="button button-secondary">Cancelar</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</main>

<footer class="footer">
<div class="container">
    <p>&copy; 2025 EduSolve - E.E.S.T. N°3. Todos los derechos reservados.</p>
</div>
</footer>
</body>
</html>
