<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../../assets/BLL/MateriaBLL.php';

$materiaBLL = new MateriaBLL();
$idMateria = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mensaje = "";

// Validar ID
if ($idMateria <= 0) {
    die("⚠ ID de materia no válido.");
}

// Obtener la materia actual
$materiaData = $materiaBLL->obtenerPorIds([$idMateria]);
$materia = !empty($materiaData) ? $materiaData[0] : null;

if (!$materia) {
    die("⚠ Materia no encontrada en la base de datos.");
}

// Procesar actualización (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $horas = (int)($_POST['horas_semanales'] ?? 0);
    $anio = (int)($_POST['anio'] ?? 0);

    if ($nombre !== '' && $descripcion !== '' && $horas > 0 && $anio > 0) {
        $ok = $materiaBLL->actualizarMateria($idMateria, $nombre, $descripcion, $horas, $anio);
        if ($ok) {
            header("Location: listado.php?msg=updated");
            exit;
        } else {
            $mensaje = "❌ Error al actualizar la materia.";
        }
    } else {
        $mensaje = "⚠ Debes completar todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Materia - EduSolve</title>
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
            <h1 class="preceptor-title">Editar Materia</h1>
            <a href="listado.php" class="button button-secondary">← Volver</a>
        </div>

        <?php if ($mensaje): ?>
            <div class="alert alert-warning" style="margin-top:1rem;">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <form class="preceptor-form" method="POST">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" 
                       value="<?= htmlspecialchars($materia->getNombre()) ?>" required>
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" required><?= htmlspecialchars($materia->getDescripcion()) ?></textarea>
            </div>

            <div class="form-group">
                <label for="horas_semanales" class="form-label">Horas semanales</label>
                <input type="number" id="horas_semanales" name="horas_semanales" class="form-control"
                       value="<?= htmlspecialchars($materia->getHorasSemanales()) ?>" required>
            </div>

            <div class="form-group">
                <label for="anio" class="form-label">Año</label>
                <input type="number" id="anio" name="anio" class="form-control"
                       value="<?= htmlspecialchars($materia->getAnio()) ?>" required>
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
        <p>&copy; 2023 EduSolve - E.E.S.T.N°3. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
