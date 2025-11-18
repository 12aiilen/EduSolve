<?php
// require_once __DIR__ . "/../controller/ProfesorController.php";
require_once __DIR__ . '/../../../assets/controllers/'; 
require_once __DIR__ . '/../../../assets/config/AbstractMapper.php';


$controller = new ProfesorController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nota = $_POST['nota'];

    if ($controller->editarCalificacion($id, $nota)) {
        header("Location: listado.php?msg=calificacion_editada");
        exit;
    } else {
        echo "Error al editar la calificación.";
    }
}

$id = $_GET['id'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Calificación</title>
    <link rel="stylesheet" href="/edusolve-moduloProfesor-copia2/assets/css/main.css">
</head>
<body>
    <div class="container">
        <h2>Editar Calificación</h2>
        <form method="POST" class="admin-form">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <div class="form-group">
                <label class="form-label">Nueva Nota:</label>
                <input type="number" step="0.01" name="nota" class="form-control" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="listado.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>