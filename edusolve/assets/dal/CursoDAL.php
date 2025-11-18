<?php
require_once __DIR__ . '/../config/AbstractMapper.php';

class CursoDAL extends AbstractMapper {
    protected string $tabla = 'cursos';

    public function __construct() {
        parent::__construct();
    }

    // Mapeo de fila a objeto (simple)
    protected function doLoad($columna): array {
        return [
            'idCursos' => $columna['idCursos'] ?? null,
            'Año' => $columna['Año'] ?? '',
            'Division' => $columna['Division'] ?? '',
            'idUsuarios' => $columna['idUsuarios'] ?? null
        ];
    }

    // Cursos por preceptor
// public function getCursoPorPreceptor(int $idPreceptor): ?array {
//     $idPreceptor = (int)$idPreceptor;
//     $sql = "SELECT * FROM cursos WHERE idUsuarios = {$idPreceptor} LIMIT 1";
//     $this->setConsulta($sql);
//     return $this->Find();
// }
public function getCursoPorPreceptor(int $idPreceptor): ?array {
    $sql = "SELECT idCursos, Año, Division, idUsuarios 
            FROM cursos 
            WHERE idUsuarios = {$idPreceptor}
            LIMIT 1";
    $this->setConsulta($sql);
    return $this->Find();
}


// 🔹 NUEVO: obtener todos los cursos que tiene asignado un preceptor
public function obtenerCursosDePreceptor(int $idPreceptor): array {
    $idPreceptor = (int)$idPreceptor;
    $sql = "SELECT idCursos, Año, Division 
            FROM cursos 
            WHERE idUsuarios = {$idPreceptor}";
    $this->setConsulta($sql);
    return $this->FindAll();
}


public function obtenerTodos(): array
{
    $consulta = "SELECT * FROM cursos";
    $this->setConsulta($consulta);
    return $this->FindAll(); // FindAll() viene de AbstractMapper
}


    // Alumnos por curso
    public function obtenerAlumnosPorCurso(int $idCurso): array {
        $idCurso = (int)$idCurso;
        $this->setConsulta("SELECT * FROM alumnos WHERE idCursos = {$idCurso}");
        return $this->FindAll();
    }
public function getCursoPorId(int $id): ?array {
    $sql = "SELECT Año, Division FROM cursos WHERE idCursos = $id";
    $this->setConsulta($sql);
    return $this->Find();
}

public function getCursosPorProfesor($profesor_id): array {
    $sql = "SELECT c.idCursos, c.Año, c.Division
            FROM cursos c
            INNER JOIN profesor_curso pc ON c.idCursos = pc.idCurso
            WHERE pc.idProfesor = {$profesor_id}";
    $this->setConsulta($sql);
    return $this->FindAll();
}

public static function getAllCursos(): array
{
    $cursoDAL = new CursoDAL();
    $lista = $cursoDAL->obtenerTodos(); // ✅ Este sí existe
    return $lista;
}

}
?>
