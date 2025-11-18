<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../BLL/AlumnoBLL.php';
require_once __DIR__ . '/../BLL/AsistenciaBLL.php';

$alumnoBLL = new AlumnoBLL();
$asistenciaBLL = new AsistenciaBLL();

// Asegurarse de que se recibe correctamente el ID del formulario
$id = isset($_POST['idAlumno']) ? (int)$_POST['idAlumno'] : 0;

if ($id > 0) {
    // Primero eliminamos todas las asistencias del alumno
    $asistenciaBLL->deleteAsistencias($id);

    // Luego eliminamos el alumno
    $alumnoBLL->deleteAlumno($id);
}

// Volver a la página de listado
header("Location: ../../pages/preceptor/estudiantes/listado.php");
exit;
?>
