<?php
require_once __DIR__ . '/../config/AbstractMapper.php';

class AsistenciaDAL extends AbstractMapper {
    protected string $tabla = 'asistencias';

    public function __construct() {
        parent::__construct();
    }

    protected function doLoad($columna): array {
        return [
            'idAsistencias' => $columna['idAsistencias'] ?? null,
            'FechaAsistencia' => $columna['FechaAsistencia'] ?? '',
            'ValorAsistencia' => $columna['ValorAsistencia'] ?? '',
            'idAlumnos' => $columna['idAlumnos'] ?? null,
            'idtipoClase' => $columna['idtipoClase'] ?? null
        ];
    }

    // Obtener asistencias por curso (une alumnos + asistencias)
    public function obtenerAsistenciasPorCurso(int $idCurso): array {
        $idCurso = (int)$idCurso;
        $sql = "
            SELECT a.idAsistencias, a.FechaAsistencia, a.ValorAsistencia,
                   al.Nombre AS NombreAlumno, al.Apellido AS ApellidoAlumno
            FROM asistencias a
            INNER JOIN alumnos al ON a.idAlumnos = al.idAlumnos
            WHERE al.idCursos = {$idCurso}
            ORDER BY a.FechaAsistencia DESC
        ";
        $this->setConsulta($sql);
        return $this->FindAll();
    }

    // Faltas totales por alumno
    public function obtenerFaltasTotalesPorAlumno(int $idAlumno): int {
        $idAlumno = (int)$idAlumno;
        $sql = "SELECT COUNT(*) AS total FROM {$this->tabla}
                WHERE idAlumnos = {$idAlumno} AND ValorAsistencia = 0";
        $this->setConsulta($sql);

        $conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->basededatos);
        $resultado = $conexion->query($sql);
        $total = 0;
        if ($fila = $resultado->fetch_assoc()) {
            $total = (int)$fila['total'];
        }
        $conexion->close();
        return $total;
    }

        // 🚀 NUEVO MÉTODO que te falta
public function deleteAsistencias(int $idAlumno): bool
{
    $sql = "DELETE FROM asistencias WHERE idAlumnos = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("i", $idAlumno);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}


}
