<?php
require_once __DIR__ . '/../config/AbstractMapper.php';
require_once __DIR__ . '/../clases/Clase.php';

class ClaseDAL extends AbstractMapper {
    protected function doLoad($row) {
        return new Clase(
            $row['id'] ?? null,
            $row['profesor_id'] ?? null,
            $row['materia_id'] ?? null,
            $row['curso_id'] ?? null,
            $row['fecha'] ?? '',
            $row['hora_inicio'] ?? '',
            $row['hora_fin'] ?? '',
            $row['descripcion'] ?? '',
            $row['estado'] ?? ''
        );
    }

    public function insertarClase(Clase $clase) {
        $sql = "INSERT INTO clases_agendadas (profesor_id, materia_id, curso_id, fecha, hora_inicio, hora_fin, descripcion, estado)
                VALUES (
                    {$clase->getProfesorId()},
                    {$clase->getMateriaId()},
                    {$clase->getCursoId()},
                    '{$clase->getFecha()}',
                    '{$clase->getHoraInicio()}',
                    '{$clase->getHoraFin()}',
                    '{$clase->getDescripcion()}',
                    '{$clase->getEstado()}'
                )";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    public function obtenerMateriasPorProfesor($profesor_id) {
        $sql = "SELECT m.id, m.nombre 
                FROM materias m
                INNER JOIN asignacion_profesores ap ON m.id = ap.materia_id
                WHERE ap.usuarios_id = $profesor_id";
        $this->setConsulta($sql);
        return $this->FindAll();
    }
}
?>
