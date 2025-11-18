<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Archivos necesarios
require_once __DIR__ . '/../../../assets/BLL/AlumnoBLL.php';
require_once __DIR__ . '/../../../assets/dal/TutorDAL.php';
require_once __DIR__ . '/../../../assets/clases/Alumno.php'; // Ajustá la ruta si es necesario

// Verificamos que venga por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recibimos los datos
    $dni = $_POST['DNI'] ?? '';
    $nombre = $_POST['Nombre'] ?? '';
    $apellido = $_POST['Apellido'] ?? '';
    $genero = $_POST['Genero'] ?? '';
    $nacionalidad = $_POST['Nacionalidad'] ?? '';
    $fechaNacimiento = $_POST['FechaNacimiento'] ?? '';
    $direccion = $_POST['Direccion'] ?? '';
    $idCurso = (int)($_POST['idCursos'] ?? 0);
    $idTipoUsuario = (int)($_POST['idTiposUsuarios'] ?? 0);
    $idTutor = (int)($_POST['idTutores'] ?? 0);

    // Verificamos que el tutor exista
    $tutorDAL = new TutorDAL();
    $tutor = $tutorDAL->findTutorByIdAlumno($idTutor);
    if (!$tutor) {
        die("Error: el tutor seleccionado no existe.");
    }

    // Creamos el objeto Alumno (ajustá según tu clase)
    $alumno = new Alumno(
        0,              // id (0 para nuevo)
        $dni,
        $nombre,
        $apellido,
        $genero,
        $nacionalidad,
        $fechaNacimiento,
        $direccion,
        $idCurso,
        $idTipoUsuario,
        $idTutor
    );

// Grabamos el alumno
$alumnoBLL = new AlumnoBLL();
$idInsertado = $alumnoBLL->GrabarAlumno($alumno);

if ($idInsertado) {
    // Redirige al listado con mensaje de éxito
    header("Location: listado.php?msg=ok&id=$idInsertado");
    exit;
} else {
    // Redirige al listado con mensaje de error
    header("Location: listado.php?msg=error");
    exit;
}

}
