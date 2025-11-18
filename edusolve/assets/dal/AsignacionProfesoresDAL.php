<?php
require_once __DIR__ . '/../config/AbstractMapper.php';

class AsignacionProfesoresDAL extends AbstractMapper {
    protected string $tabla = 'asignacion_profesores';

    public function __construct() {
        parent::__construct();
    }

    // Mapeo de fila a objeto simple
    protected function doLoad($columna) {
        return [
            'id' => $columna['id'] ?? null,
            'usuarios_id' => $columna['usuarios_id'] ?? null,
            'materia_id' => $columna['materia_id'] ?? null,
            'curso_id' => $columna['curso_id'] ?? null,
            'horario' => $columna['horario'] ?? null
        ];
    }

    // 🔹 Obtener los cursos asignados a un preceptor
    // public function getCursosPorPreceptor(int $idPreceptor): array {
    //     $idPreceptor = (int)$idPreceptor;
    //     $sql = "SELECT DISTINCT curso_id FROM {$this->tabla} WHERE usuarios_id = {$idPreceptor}";
    //     $this->setConsulta($sql);
    //     $resultados = $this->FindAll();

    //     // Si doLoad transforma las columnas, las normalizamos
    //     $cursos = [];
    //     foreach ($resultados as $fila) {
    //         if (isset($fila['curso_id'])) {
    //             $cursos[] = ['curso_id' => $fila['curso_id']];
    //         }
    //     }
    //     return $cursos;
    // }
public function getCursosPorPreceptor(int $idPreceptor): array {
    // Busca los cursos del preceptor en la tabla "cursos"
    $sql = "SELECT idCursos AS curso_id, Año, Division 
            FROM cursos 
            WHERE idUsuarios = {$idPreceptor}";
    
    $this->setConsulta($sql);
    return $this->FindAll();
}


public function getCursosPorProfesor(int $idProfesor): array {
    $sql = "SELECT DISTINCT curso_id 
            FROM {$this->tabla} 
            WHERE usuarios_id = {$idProfesor}";
    $this->setConsulta($sql);
    return $this->FindAll();
}


    // 🔹 Obtener las materias asignadas a un curso
    public function getMateriasPorCurso(int $idCurso): array {
        $idCurso = (int)$idCurso;
        $sql = "SELECT materia_id FROM {$this->tabla} WHERE curso_id = {$idCurso}";
        $this->setConsulta($sql);
        $resultados = $this->FindAll();

        $materias = [];
        foreach ($resultados as $fila) {
            if (isset($fila['materia_id'])) {
                $materias[] = ['materia_id' => $fila['materia_id']];
            }
        }
        return $materias;
    }

    // 🔹 Obtener todas las asignaciones completas de un preceptor
    public function getAsignacionesPorPreceptor(int $idPreceptor): array {
        $idPreceptor = (int)$idPreceptor;
        $sql = "SELECT * FROM {$this->tabla} WHERE usuarios_id = {$idPreceptor}";
        $this->setConsulta($sql);
        return $this->FindAll();
    }


        // 🔹 Insertar nueva asignación
    public function insertar(int $usuarios_id, int $materia_id, int $curso_id, string $horario): int|bool|string {
        $sql = "INSERT INTO {$this->tabla} (usuarios_id, materia_id, curso_id, horario)
                VALUES ($usuarios_id, $materia_id, $curso_id, '$horario')";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    // 🔹 Actualizar una asignación existente
    public function actualizar(int $id, int $usuarios_id, int $materia_id, int $curso_id, string $horario): bool {
        $sql = "UPDATE {$this->tabla}
                SET usuarios_id = $usuarios_id, materia_id = $materia_id, curso_id = $curso_id, horario = '$horario'
                WHERE id = $id";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    // 🔹 Eliminar una asignación por ID
    public function eliminar(int $id): bool {
        $sql = "DELETE FROM {$this->tabla} WHERE id = $id";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    // 🔹 Obtener una asignación específica
    public function obtenerPorId(int $id): ?array {
        $sql = "SELECT * FROM {$this->tabla} WHERE id = $id";
        $this->setConsulta($sql);
        return $this->Find();
    }

    // 🔹 Obtener todas las asignaciones
    public function obtenerTodas(): array {
        $sql = "SELECT * FROM {$this->tabla}";
        $this->setConsulta($sql);
        return $this->FindAll(
        );
    }

public function obtenerMateriasPorProfesor($idProfesor): array {
    $materias = [];

    $sql = "SELECT materia_id FROM asignacion_profesores WHERE usuarios_id = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("i", $idProfesor);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        $materias[] = $fila['materia_id'];
    }

    $stmt->close();
    return $materias;
}




}
?>
