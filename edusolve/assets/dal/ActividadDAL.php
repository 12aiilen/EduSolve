<?php
require_once __DIR__ . '/../config/AbstractMapper.php';

class ActividadDAL extends AbstractMapper {
    protected string $tabla = 'actividad_reciente';

    // Registrar una nueva actividad
    public function registrarActividad(int $idUsuario, string $descripcion): bool {
        $idUsuario = (int)$idUsuario;
        $descripcion = $this->conexion->real_escape_string($descripcion);

        $sql = "INSERT INTO {$this->tabla} (idUsuario, descripcion, fecha)
                VALUES ($idUsuario, '$descripcion', NOW())";

        return $this->conexion->query($sql);
    }

    // Obtener las últimas actividades (todas)
    public function obtenerActividadesRecientes(int $limite = 10): array {
        $sql = "SELECT ar.id, ar.descripcion, ar.fecha, u.Nombre, u.Apellido
                FROM {$this->tabla} ar
                INNER JOIN usuarios u ON ar.idUsuario = u.idUsuarios
                ORDER BY ar.fecha DESC
                LIMIT $limite";

        $this->setConsulta($sql);
        return $this->FindAll();
    }

    // 🔹 NUEVO: Obtener las actividades de un usuario específico
    public function obtenerActividadesPorUsuario(int $idUsuario, int $limite = 10): array {
        $sql = "SELECT ar.id, ar.descripcion, ar.fecha, u.Nombre, u.Apellido
                FROM {$this->tabla} ar
                INNER JOIN usuarios u ON ar.idUsuario = u.idUsuarios
                WHERE ar.idUsuario = $idUsuario
                ORDER BY ar.fecha DESC
                LIMIT $limite";

        $this->setConsulta($sql);
        return $this->FindAll();
    }

    // Implementación obligatoria de doLoad()
    protected function doLoad($row) {
        return [
            'id' => $row['id'],
            'descripcion' => $row['descripcion'],
            'fecha' => $row['fecha'],
            'nombre' => $row['Nombre'] ?? '',
            'apellido' => $row['Apellido'] ?? ''
        ];
    }
}
