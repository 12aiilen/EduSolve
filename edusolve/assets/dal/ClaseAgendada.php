<?php
require_once __DIR__ . '/../config/AbstractMapper.php';
require_once __DIR__ . '/../clases/ClaseAgendada.php';

class ClaseAgendadaDAL extends AbstractMapper {
    protected string $tabla = 'clases_agendadas';

    protected function doLoad($columna) {
        return new ClaseAgendada(
            (int)$columna['profesor_id'],
            (int)$columna['materia_id'],
            (int)$columna['curso_id'],
            (string)$columna['fecha'],
            (string)$columna['hora_inicio'],
            (string)$columna['hora_fin'],
            (string)$columna['descripcion'],
            (string)$columna['estado'],
            (int)$columna['id']
        );
    }

    public function insertar(ClaseAgendada $clase): bool {
        $sql = sprintf(
            "INSERT INTO clases_agendadas 
             (profesor_id, materia_id, curso_id, fecha, hora_inicio, hora_fin, descripcion, estado)
             VALUES (%d, %d, %d, '%s', '%s', '%s', '%s', '%s')",
            $clase->getProfesorId(),
            $clase->getMateriaId(),
            $clase->getCursoId(),
            $clase->getFecha(),
            $clase->getHoraInicio(),
            $clase->getHoraFin(),
            $this->conexion->real_escape_string($clase->getDescripcion()),
            $clase->getEstado()
        );
        $this->setConsulta($sql);
        return $this->Execute();
    }

    public function obtenerPorProfesor(int $profesor_id): array {
        $sql = "SELECT * FROM clases_agendadas WHERE profesor_id = $profesor_id ORDER BY fecha DESC";
        $this->setConsulta($sql);
        return $this->FindAll();
    }
}
?>
