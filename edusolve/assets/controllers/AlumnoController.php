<?php
require_once __DIR__ . '/../dal/AlumnoDAL.php';
require_once __DIR__ . '/../BLL/AlumnoBLL.php';

class AlumnoController {

    private AlumnoDAL $dal;
    private AlumnoBLL $bll;

    public function __construct() {
        $this->dal = new AlumnoDAL();
        $this->bll = new AlumnoBLL();
    }

    public function obtenerAlumnoActual(): ?Alumno {
    // Verifica si hay sesión activa
    if (!isset($_SESSION)) {
        session_start();
    }

    // Verifica si los datos del alumno están en sesión
    if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
        return null;
    }

    $nombre = $_SESSION['nombre'];
    $apellido = $_SESSION['apellido'];

    // Usa el DAL para buscar el ID del alumno
    require_once __DIR__ . '/../dal/AlumnoDAL.php';
    $dal = new AlumnoDAL();

    $idAlumno = $dal->getIdAlumnoPorNombreApellido($nombre, $apellido);

    if ($idAlumno === null) {
        error_log("❌ No se encontró el alumno con nombre: $nombre $apellido");
        return null;
    }

    // Retorna el objeto Alumno completo
    return $dal->getById($idAlumno);
}



    public function obtenerTodos(): array {
        return $this->dal->getAll();
    }

    public function obtenerPorId(int $id): ?Alumno {
        return $this->dal->getById($id);
    }

    public function obtenerAprobados(): array {
        return $this->dal->getAprobados();
    }

    public function obtenerDestacados(): array {
        return $this->dal->getDestacados();
    }

    public function obtenerPromedioGeneral(): float {
        return $this->dal->getPromedioGeneral();
    }

    // ✅ Obtener ID del alumno según su nombre y apellido
    public function obtenerIdAlumnoPorNombreApellido(string $nombre, string $apellido): ?int {
        return $this->bll->getIdAlumnoPorNombreApellido($nombre, $apellido);
    }


    // ✅ Obtener materias por alumno
    public function obtenerMaterias(int $idAlumno): array {
        return $this->dal->getMateriasPorAlumno($idAlumno);
    }

    // ✅ Obtener calificaciones por alumno
    public function obtenerCalificaciones(int $idAlumno): array {
        return $this->dal->getCalificacionesPorAlumno($idAlumno);
    }

    
}
