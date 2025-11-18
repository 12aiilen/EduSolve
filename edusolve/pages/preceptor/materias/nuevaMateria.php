<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../../assets/DAL/CursoDAL.php';
require_once __DIR__ . '/../../../assets/BLL/MateriaBLL.php';
require_once __DIR__ . '/../../../assets/DAL/MateriaDAL.php';

// Verificar sesión del preceptor
$idPreceptor = $_SESSION['idPreceptor'] ?? 0;
if ($idPreceptor <= 0) {
    echo "⚠️ No hay un preceptor identificado en sesión.";
    exit;
}

// Traer curso del preceptor
$cursoDAL = new CursoDAL();
$curso = $cursoDAL->getCursoPorPreceptor($idPreceptor);

if (!$curso) {
    echo "⚠️ No hay un curso asociado al preceptor.";
    exit;
}
$idCurso = (int)$curso['idCursos'];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $horas = (int)($_POST['horas_semanales'] ?? 0);
    $anio = (int)($_POST['anio'] ?? 1);

    if ($nombre) {
        $materiaDAL = new MateriaDAL();
        $materiaId = $materiaDAL->insertar($nombre, $descripcion, $horas, $anio);

        // Si necesitás guardar el curso asociado, agregá un método en DAL para hacerlo.
        // Por ahora solo crea la materia.

        header("Location: listado.php");
        exit;
    } else {
        $error = "⚠️ Debes completar todos los campos obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Materia - EduSolve</title>
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
            <h1 class="preceptor-title">Registrar Nueva Materia</h1>
            <div class="preceptor-actions">
                <a href="listado.php" class="button button-secondary">← Volver al listado</a>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="post" class="preceptor-form">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Descripción:</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Horas Semanales:</label>
                <input type="number" name="horas_semanales" class="form-control" value="0" min="0">
            </div>

            <div class="form-group">
                <label>Año:</label>
                <input type="number" name="anio" class="form-control" value="1" min="1">
            </div>

            <div class="form-actions">
                <button type="submit" class="button button-primary">Registrar Materia</button>
                <a href="listado.php" class="button button-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; 2025 EduSolve - E.E.S.T. N°3. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
