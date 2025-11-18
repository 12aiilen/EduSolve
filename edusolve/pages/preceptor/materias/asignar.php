<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../../assets/dal/AsignacionProfesoresDAL.php';
require_once __DIR__ . '/../../../assets/dal/usuariosDAL.php';
require_once __DIR__ . '/../../../assets/dal/MateriaDAL.php';
require_once __DIR__ . '/../../../assets/dal/CursoDAL.php';
require_once __DIR__ . '/../../../assets/dal/ActividadDAL.php';

$asignacionDAL = new AsignacionProfesoresDAL();
$usuariosDAL = new UsuarioDAL();
$materiasDAL = new MateriaDAL();
$cursosDAL = new CursoDAL();
$actividadDAL = new ActividadDAL();

$mensaje = "";

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarios_id = $_POST['usuarios_id'] ?? null;
    $materia_id = $_POST['materia_id'] ?? null;
    $curso_id = $_POST['curso_id'] ?? null;
    $horario = $_POST['horario'] ?? '';

    if ($usuarios_id && $materia_id && $curso_id && !empty($horario)) {
        $resultado = $asignacionDAL->insertar(
            (int)$usuarios_id,
            (int)$materia_id,
            (int)$curso_id,
            $horario
        );

        $mensaje = $resultado ? "✅ Asignación registrada correctamente." : "❌ Error al registrar la asignación.";

        // 🔹 Registrar en actividad reciente si fue exitoso
        if ($resultado && isset($_SESSION['idPreceptor'])) {
            $preceptorId = (int)$_SESSION['idPreceptor'];

            // Obtener nombres para la descripción
            $profesorNombre = $usuariosDAL->obtenerNombrePorId((int)$usuarios_id);
            $materiaNombre = $materiasDAL->obtenerNombrePorId((int)$materia_id);
            $cursoInfo = $cursosDAL->getCursoPorId((int)$curso_id);

            $descripcion = "Asignó al profesor $profesorNombre a la materia $materiaNombre del curso {$cursoInfo['Año']}° {$cursoInfo['Division']}";
            $actividadDAL->registrarActividad($preceptorId, $descripcion);
        }

    } else {
        $mensaje = "⚠️ Debes completar todos los campos.";
    }
}

// Obtener datos para los selects
$profesores = $usuariosDAL->obtenerPorTipoUsuario(4); // Profesores
$materias = $materiasDAL->obtenerTodas();

// 🔹 Si el usuario logueado es un preceptor, solo mostrar sus cursos
if (isset($_SESSION['idPreceptor'])) {
    $idPreceptor = (int)$_SESSION['idPreceptor'];
    $cursos = $cursosDAL->obtenerCursosDePreceptor($idPreceptor);
} else {
    $cursos = $cursosDAL->obtenerTodos();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Profesor a Materia</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../assets/css/preceptor.css">
    <link rel="icon" href="../../../assets/images/escudo.png" type="image/png">
</head>
<body>
<header class="header">
    <div class="container">
        <div class="logo-container">
            <img src="../../../assets/images/escudo.png" alt="Escudo" class="header-logo">
            <a href="../../../index.php" class="logo">EduSolve</a>
        </div>
        <nav class="nav">
            <ul class="nav-list">
                <li><a href="../homePreceptor.php" class="nav-link">Inicio</a></li>
                <li><a href="../estudiantes/listado.php" class="nav-link">Estudiantes</a></li>
                <li><a href="listado.php" class="nav-link active">Materias</a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button></li>
            </ul>
        </nav>
    </div>
</header>

<main class="preceptor-panel">
    <div class="container">
        <div class="preceptor-header">
            <h1 class="preceptor-title">Asignar Profesor a Materia y Curso</h1>
            <a href="listado.php" class="button button-secondary">← Volver</a>
        </div>

        <?php if ($mensaje): ?>
            <div class="alert alert-warning" style="margin-top:1rem;">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <form class="preceptor-form" method="POST">
            <div class="form-group">
                <label for="usuarios_id" class="form-label">Profesor</label>
                <select name="usuarios_id" id="usuarios_id" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($profesores as $p): ?>
                        <option value="<?= $p->getIdUsuarios() ?>">
                            <?= htmlspecialchars($p->getApellido() . ' ' . $p->getNombre()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="materia_id" class="form-label">Materia</label>
                <select name="materia_id" id="materia_id" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($materias as $m): ?>
                        <option value="<?= $m->getId() ?>">
                            <?= htmlspecialchars($m->getNombre()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="curso_id" class="form-label">Curso</label>
                <select name="curso_id" id="curso_id" class="form-control" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['idCursos'] ?>">
                            <?= htmlspecialchars($c['Año'] . 'º ' . $c['Division']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="horario" class="form-label">Horario</label>
                <input type="text" id="horario" name="horario" class="form-control"
                       placeholder="Ej: Martes 17:45-19:45" required>
            </div>

            <div class="form-actions">
                <a href="listado.php" class="button button-secondary">Cancelar</a>
                <button type="submit" class="button button-primary">Asignar</button>
            </div>
        </form>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
