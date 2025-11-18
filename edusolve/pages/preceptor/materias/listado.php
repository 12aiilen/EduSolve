<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../../../assets/BLL/MateriaBLL.php';
require_once __DIR__ . '/../../../assets/DAL/AsignacionProfesoresDAL.php';
require_once __DIR__ . '/../../../assets/DAL/CursoDAL.php';

// Verificar sesión del preceptor
$idPreceptor = $_SESSION['idPreceptor'] ?? 0;
if ($idPreceptor <= 0) {
    echo "⚠ No hay un preceptor identificado en sesión.";
    exit;
}

// Verificar curso en sesión o buscarlo
$idCurso = $_SESSION['idCurso'] ?? null;
if (empty($idCurso)) {
    $cursoDAL = new CursoDAL();
    $curso = $cursoDAL->getCursoPorPreceptor($idPreceptor);
    if ($curso) {
        $idCurso = (int)$curso['idCursos'];
        $_SESSION['idCurso'] = $idCurso;
    } else {
        echo "⚠ No hay un curso asociado al preceptor.";
        exit;
    }
}

// Obtener materias asignadas al curso
$materiaBLL = new MateriaBLL();
$asigDAL = new AsignacionProfesoresDAL();
$asignaciones = $asigDAL->getMateriasPorCurso((int)$idCurso);
$materias = [];


if (!empty($asignaciones)) {
    $materiaIds = array_unique(array_column($asignaciones, 'materia_id'));
    $materias = $materiaBLL->obtenerPorIds($materiaIds);
}


if (!is_array($materias)) {
    $materias = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias del Curso - EduSolve</title>
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
                <li><a href="#" class="nav-link active">Materias</a></li>
                    <button id="logoutBtn" class="nav-button logout-btn" onclick="window.location.href='../../auth/logout.php'">Cerrar Sesión</button></li>
            </ul>
        </nav>
    </div>
</header>

<main class="preceptor-panel">
    <div class="container">
        <div class="preceptor-header">
            <h1 class="preceptor-title">Materias del Curso</h1>
            <div class="preceptor-actions">
                <a href="nuevaMateria.php" class="button button-primary">+ Nueva Materia</a>
                <a href="../homePreceptor.php" class="button button-secondary">← Volver</a>
            </div>
        </div>

        <div class="preceptor-search">
            <input type="text" id="searchInput" placeholder="Buscar materia..." class="form-control">
        </div>

        <table class="preceptor-table" id="materiasTable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Horas Semanales</th>
                    <th>Año</th>
                    <th>Acciones</th>
                </tr>
            </thead>
<tbody>
    <?php if (!empty($materias)): ?>
        <?php foreach ($materias as $materia): ?>
            <tr>
                <td><?= htmlspecialchars($materia->getNombre()) ?></td>
                <td><?= htmlspecialchars($materia->getDescripcion()) ?></td>
                <td><?= htmlspecialchars($materia->getHorasSemanales()) ?></td>
                <td><?= htmlspecialchars($materia->getAnio()) ?></td>
                <td>
                    <a href="editar.php?id=<?= $materia->getId() ?>" class="button button-primary">Editar</a>
                    <form method="POST" action="../../../assets/controllers/deleteMateria.control.php" style="display:inline;">
                        <input type="hidden" name="idMateria" value="<?= $materia->getId() ?>">
                        <button type="submit" class="button button-secondary"
                            onclick="return confirm('¿Estás seguro de eliminar esta materia?');">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">No hay materias asignadas a este curso.</td></tr>
    <?php endif; ?>
</tbody>

        </table>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <p>&copy; 2025 EduSolve - E.E.S.T. N°3. Todos los derechos reservados.</p>
    </div>
</footer>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#materiasTable tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let match = false;
        cells.forEach(cell => {
            if (cell.textContent.toLowerCase().includes(filter)) match = true;
        });
        row.style.display = match ? '' : 'none';
    });
});
</script>
</body>
</html>
