<?php
require_once __DIR__ . '/../DAL/AsignacionProfesoresDAL.php';

class AsignacionProfesoresBLL {
    private AsignacionProfesoresDAL $dal;

    public function __construct() {
        $this->dal = new AsignacionProfesoresDAL();
    }

    public function obtenerTodas(): array {
        return $this->dal->obtenerTodas();
    }

    public function insertar(int $usuarios_id, int $materia_id, int $curso_id, string $horario): bool|int|string {
        return $this->dal->insertar($usuarios_id, $materia_id, $curso_id, $horario);
    }

    public function actualizar(int $id, int $usuarios_id, int $materia_id, int $curso_id, string $horario): bool {
        return $this->dal->actualizar($id, $usuarios_id, $materia_id, $curso_id, $horario);
    }

    public function eliminar(int $id): bool {
        return $this->dal->eliminar($id);
    }

    public function obtenerPorId(int $id): ?array {
        return $this->dal->obtenerPorId($id);
    }

    public function getCursosPorPreceptor(int $idPreceptor): array {
        return $this->dal->getCursosPorPreceptor($idPreceptor);
    }

    public function getCursosPorProfesor(int $idProfesor): array {
        return $this->dal->getCursosPorProfesor($idProfesor);
    }

    public function getMateriasPorCurso(int $idCurso): array {
        return $this->dal->getMateriasPorCurso($idCurso);
    }

    public function getAsignacionesPorPreceptor(int $idPreceptor): array {
        return $this->dal->getAsignacionesPorPreceptor($idPreceptor);
    }
}
?>
