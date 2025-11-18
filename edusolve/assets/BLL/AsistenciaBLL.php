<?php
require_once __DIR__ . '/../DAL/AsistenciaDAL.php';

class AsistenciaBLL
{
    private AsistenciaDAL $asistenciaDAL;

    public function __construct()
    {
        // ⚠️ Esto inicializa correctamente la DAL
        $this->asistenciaDAL = new AsistenciaDAL();
    }

    public function deleteAsistencias(int $idAlumno): bool
    {
        // ⚠️ Usa la DAL para eliminar
        return $this->asistenciaDAL->deleteAsistencias($idAlumno);
    }

    // Ejemplo: otros métodos posibles
    public function registrarAsistencia($asistencia): bool
    {
        return $this->asistenciaDAL->insert($asistencia);
    }

    public function obtenerAsistenciasPorAlumno(int $idAlumno): array
    {
        return $this->asistenciaDAL->getByAlumnoId($idAlumno);
    }
}
