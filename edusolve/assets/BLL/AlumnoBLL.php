<?php
require_once __DIR__ . '/../dal/AlumnoDAL.php';

class AlumnoBLL
{
    private AlumnoDAL $dal;

    public function __construct()
    {
        $this->dal = new AlumnoDAL();
    }

    // ✅ Guardar un nuevo alumno
    public function grabarAlumno(Alumno $alumno): int
    {
        // Si tenés el método InsertarAlumno, usalo; si no, lo podemos agregar en DAL
        if (method_exists($this->dal, 'InsertarAlumno')) {
            return $this->dal->InsertarAlumno($alumno);
        } else {
            throw new Exception("El método InsertarAlumno no está definido en AlumnoDAL.");
        }
    }



    // ✅ Obtener ID del alumno por DNI
    public function getIdAlumnoDni(string $dni): ?int
    {
        return $this->dal->findId($dni);
    }

    // ✅ Obtener alumno por su ID
    public function getAlumnoById(int $idAlumno): ?Alumno
    {
        return $this->dal->getById($idAlumno);
    }

    // ✅ Obtener lista de alumnos por curso (si existe el método en DAL)
    public function getAlumnosByIdCurso(int $idCurso): ?array
    {
        if (method_exists($this->dal, 'findAlumnosByIdCurso')) {
            return $this->dal->findAlumnosByIdCurso($idCurso);
        }

        // Si no existe, devolvemos null sin romper el flujo
        return null;
    }

    // ✅ Listar todos los alumnos
    public static function listaAlumnos(): array
    {
        $AlumnoDAL = new AlumnoDAL();
        $lista = $AlumnoDAL->getAll();
        return $lista;
    }
public function deleteAlumno(int $idAlumno): bool
{
    return $this->dal->deleteAlumno($idAlumno);
}






// ✅ Actualizar alumno
public function updateAlumno(Alumno $alumno): bool
{
    if (method_exists($this->dal, 'UpdateAlumno')) {
        return $this->dal->UpdateAlumno($alumno);
    }
    return false;
}


public function obtenerEstudiantesPorPreceptor(int $idPreceptor): array
{
    $alumnoDAL = new AlumnoDAL();
    return $alumnoDAL->obtenerEstudiantesPorPreceptor($idPreceptor);
}


public function getTurnos(): array {
    return $this->dal->getTurnos();
}


    //vero
    public function getIdAlumnoPorNombreApellido(string $nombre, string $apellido): ?int {
    $alumnoDAL = new AlumnoDAL();
    $id = $alumnoDAL->getIdAlumnoPorNombreApellido($nombre, $apellido);
    return $id;
}


}
