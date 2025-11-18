<?php
require_once __DIR__ . '/../config/AbstractMapper.php';
require_once __DIR__ . '/../clases/Alumno.php';

class AlumnoDAL extends AbstractMapper {

    protected string $tabla = 'alumnos';

    // ✅ Obtener todos los alumnos
    public function getAll(): array {
        $this->setConsulta("SELECT * FROM alumnos");
        return $this->FindAll();
    }

    // ✅ Obtener alumnos aprobados (promedio >= 6)
    public function getAprobados(): array {
        $this->setConsulta("
            SELECT a.idAlumnos, a.DNI, a.Nombre, a.Apellido, a.Genero, a.Nacionalidad,
                   a.FechaNacimiento, a.Direccion, a.idCursos, a.idTiposUsuarios, a.idTutores
            FROM alumnos a
            JOIN progreso_academico p ON a.idAlumnos = p.id_alumno
            WHERE p.promedio_general >= 6
        ");
        return $this->FindAll();
    }

    // ✅ Obtener alumnos destacados (promedio >= 9)
    public function getDestacados(): array {
        $this->setConsulta("
            SELECT a.idAlumnos, a.DNI, a.Nombre, a.Apellido, a.Genero, a.Nacionalidad,
                   a.FechaNacimiento, a.Direccion, a.idCursos, a.idTiposUsuarios, a.idTutores
            FROM alumnos a
            JOIN progreso_academico p ON a.idAlumnos = p.id_alumno
            WHERE p.promedio_general >= 9
        ");
        return $this->FindAll();
    }

    // ✅ Obtener promedio general de todos los alumnos
    public function getPromedioGeneral(): float {
        $result = $this->conexion->query("SELECT AVG(promedio_general) AS prom FROM progreso_academico");
        if ($result && $row = $result->fetch_assoc()) {
            return (float)$row['prom'];
        }
        return 0.0;
    }

    // ✅ Obtener un alumno por ID
    public function getById(int $id): ?Alumno {
        $this->setConsulta("SELECT * FROM alumnos WHERE idAlumnos = $id");
        return $this->Find();
    }

    // ✅ Buscar alumno por DNI (para vincular al usuario logueado)
    public function findId(string $dni): ?int {
        $query = "SELECT idAlumnos FROM alumnos WHERE DNI = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return (int)$row['idAlumnos'];
        }
        return null;
    }

    // ✅ NUEVO: insertar un alumno
    // public function InsertarAlumno(Alumno $alumno): int {
    //     $sql = "INSERT INTO alumnos (DNI, Nombre, Apellido, Genero, Nacionalidad, FechaNacimiento, Direccion, idCursos, idTiposUsuarios, idTutores)
    //             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //     $stmt = $this->conexion->prepare($sql);
    //     $stmt->bind_param(
    //         "sssssssiii",
    //         $alumno->getDNI(),
    //         $alumno->getNombre(),
    //         $alumno->getApellido(),
    //         $alumno->getGenero(),
    //         $alumno->getNacionalidad(),
    //         $alumno->getFechaNacimiento(),
    //         $alumno->getDireccion(),
    //         $alumno->getIdCursos(),
    //         $alumno->getIdTiposUsuarios(),
    //         $alumno->getIdTutores()
    //     );

    //     if ($stmt->execute()) {
    //         return $this->conexion->insert_id;
    //     }
    //     return 0;
    // }


 public function InsertarAlumno(Alumno $alumno): int {
    $sql = "INSERT INTO alumnos 
        (DNI, Nombre, Apellido, Genero, Nacionalidad, FechaNacimiento, Direccion, idCursos, idTiposUsuarios, idTutores, idTurno)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conexion->prepare($sql);

    $stmt->bind_param(
        "sssssssiiii",
        $alumno->getDNI(),
        $alumno->getNombre(),
        $alumno->getApellido(),
        $alumno->getGenero(),
        $alumno->getNacionalidad(),
        $alumno->getFechaNacimiento(),
        $alumno->getDireccion(),
        $alumno->getIdCursos(),
        $alumno->getIdTiposUsuarios(),
        $alumno->getIdTutores(),
        $alumno->getIdTurno()   // ✅ pasar idTurno
    );

    if ($stmt->execute()) {
        return $this->conexion->insert_id;
    }
    return 0;
}


    // ✅ NUEVO: buscar alumnos por curso
    public function findAlumnosByIdCurso(int $idCurso): array {
        $sql = "SELECT * FROM alumnos WHERE idCursos = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idCurso);
        $stmt->execute();
        $result = $stmt->get_result();

        $lista = [];
        while ($row = $result->fetch_assoc()) {
            $lista[] = $this->doLoad($row);
        }

        return $lista;
    }

    // ✅ Implementación obligatoria del AbstractMapper
// Implementación obligatoria del AbstractMapper
protected function doLoad($columna): Alumno {
    return new Alumno(
        (int)$columna['idAlumnos'],
        $columna['DNI'] ?? '',
        $columna['Nombre'] ?? '',
        $columna['Apellido'] ?? '',
        $columna['Genero'] ?? '',
        $columna['Nacionalidad'] ?? '',
        $columna['FechaNacimiento'] ?? '',
        $columna['Direccion'] ?? '',
        (int)($columna['idCursos'] ?? 0),
        (int)($columna['idTiposUsuarios'] ?? 0),
        (int)($columna['idTutores'] ?? 0),
        (int)($columna['idTurno'] ?? 0),
        $columna['turnoNombre'] ?? null // ✅ Ahora siempre se carga el nombre del turno
    );
}






public function getMateriasPorAlumno(int $idAlumno): array
{
    $materias = [];

    $sql = "
        SELECT DISTINCT 
            m.id, 
            m.nombre, 
            m.descripcion, 
            m.horas_semanales, 
            m.anio
        FROM alumnos a
        INNER JOIN asignacion_profesores ap ON a.idCursos = ap.curso_id
        INNER JOIN materias m ON ap.materia_id = m.id
        WHERE a.idAlumnos = ?
    ";

    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("i", $idAlumno);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $materias[] = $row;
    }

    $stmt->close();
    return $materias;
}



public function getCalificacionesPorAlumno(int $idAlumno): array
{
    $calificaciones = [];

    $sql = "
        SELECT 
            c.id AS id_calificacion,
            m.nombre AS materia,
            c.calificacion,
            c.fecha,
            c.observaciones,
            te.nombre AS tipo_evaluacion,
            u.Nombre AS profesor_nombre,
            u.Apellido AS profesor_apellido
        FROM calificaciones c
        INNER JOIN materias m ON c.materia_id = m.id
        INNER JOIN tipos_evaluacion te ON c.tipo_evaluacion_id = te.id
        INNER JOIN usuarios u ON c.profesor_id = u.idUsuarios
        WHERE c.estudiante_id = ?
        ORDER BY c.fecha DESC
    ";

    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("i", $idAlumno);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $calificaciones[] = $row;
    }

    $stmt->close();
    return $calificaciones;
}



public function getIdAlumnoPorNombreApellido(string $nombre, string $apellido): ?int {
    $nombre = $this->conexion->real_escape_string($nombre);
    $apellido = $this->conexion->real_escape_string($apellido);

    $sql = "
        SELECT idAlumnos 
        FROM alumnos 
        WHERE LOWER(TRIM(Nombre)) = LOWER(TRIM('$nombre'))
          AND LOWER(TRIM(Apellido)) = LOWER(TRIM('$apellido'))
        LIMIT 1
    ";

    $resultado = $this->conexion->query($sql);

    if ($resultado && $row = $resultado->fetch_assoc()) {
        return (int)$row['idAlumnos'];
    }

    return null;
}
public function obtenerEstudiantesPorPreceptor(int $idPreceptor): array
{
    $sql = "
        SELECT 
            a.*, 
            t.nombre AS turnoNombre
        FROM alumnos a
        INNER JOIN cursos c ON a.idCursos = c.idCursos
        LEFT JOIN turnos t ON a.idTurno = t.id
        WHERE c.idUsuarios = ?
    ";

    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("i", $idPreceptor);
    $stmt->execute();
    $result = $stmt->get_result();

    $alumnos = [];
    while ($row = $result->fetch_assoc()) {
        $alumnos[] = $this->doLoad($row); // ✅ doLoad carga turnoNombre
    }

    $stmt->close();
    return $alumnos;
}







    // ✅ Actualizar un alumno existente
public function UpdateAlumno(Alumno $alumno): bool {
    $sql = "
        UPDATE alumnos
        SET Nombre = ?, Apellido = ?, DNI = ?, Direccion = ?, idTurno = ?
        WHERE idAlumnos = ?
    ";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param(
        "ssssii",
        $alumno->getNombre(),
        $alumno->getApellido(),
        $alumno->getDNI(),
        $alumno->getDireccion(),
        $alumno->getIdTurno(),
        $alumno->getId()
    );
    return $stmt->execute();
}


public function deleteAlumno(int $idAlumno): bool
{
    $sql = "DELETE FROM alumnos WHERE idAlumnos = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("i", $idAlumno);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}


public function getTurnos(): array {
    $turnos = [];
    $sql = "SELECT id, nombre FROM turnos ORDER BY id";
    $result = $this->conexion->query($sql);
    while ($row = $result->fetch_assoc()) {
        $turnos[] = $row;
    }
    return $turnos;
}




}
