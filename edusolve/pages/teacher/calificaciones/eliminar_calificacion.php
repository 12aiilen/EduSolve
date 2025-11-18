<?php
require_once __DIR__ . '/controller/ProfesorController.php';

$controller = new ProfesorController();

$id = $_GET['id'] ?? null;

if ($id && $controller->eliminarCalificacion($id)) {
    header("Location: listado.php?msg=calificacion_eliminada");
    exit;
} else {
    echo "Error al eliminar calificación.";
}