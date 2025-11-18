<?php
require_once __DIR__ . '/../config/AbstractMapper.php';
require_once __DIR__ . '/../clases/InformeAsistencia.php';

class InformeAsistenciaDAL extends AbstractMapper {

    protected string $tabla = "informe_asistencia";

protected function doLoad($columna) {
    return new InformeAsistencia(
        $columna['idAlumno'],
        $columna['nombre'],
        $columna['apellido'],
        $columna['tipoClase'] ?? '', // Si es null, asigna string vacío
        $columna['valorAsistencia'] ?? 0.0, // Si es null, asigna 0.0
        isset($columna['fechaAsistencia']) ? new DateTime($columna['fechaAsistencia']) : new DateTime(), // si null, fecha actual
        $columna['faltasTotales'] ?? 0
    );
}

public function generarInformePorPreceptor($idPreceptor) {
    $this->setConsulta("
        SELECT 
            a.idAlumnos AS idAlumno,
            a.Nombre AS nombre,
            a.Apellido AS apellido,
            tc.tipoClase AS tipoClase,
            asis.ValorAsistencia AS valorAsistencia,
            asis.FechaAsistencia AS fechaAsistencia,
            COALESCE(
                (SELECT SUM(1 - ValorAsistencia) 
                 FROM asistencias 
                 WHERE idAlumnos = a.idAlumnos), 0
            ) AS faltasTotales
        FROM alumnos a
        JOIN cursos c ON a.idCursos = c.idCursos
        JOIN usuarios u ON c.idUsuarios = u.idUsuarios
        LEFT JOIN asistencias asis ON a.idAlumnos = asis.idAlumnos
        LEFT JOIN tipoclase tc ON asis.idtipoClase = tc.idtipoClase
        WHERE u.idUsuarios = $idPreceptor
    ");
    return $this->FindAll();
}


}


?>