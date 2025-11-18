<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../BLL/MateriaBLL.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "❌ Método no permitido.";
    exit;
}

$idMateria = $_POST['idMateria'] ?? 0;
$idMateria = (int)$idMateria;

if ($idMateria <= 0) {
    echo "⚠ ID de materia inválido.";
    exit;
}

$materiaBLL = new MateriaBLL();

if ($materiaBLL->eliminarMateria($idMateria)) {
    header("Location: ../../pages/preceptor/materias/listado.php?msg=deleted");
    exit;
} else {
    header("Location: ../../pages/preceptor/materias/listado.php?msg=error");
    exit;
}
