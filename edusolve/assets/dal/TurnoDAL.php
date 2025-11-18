<?php
require_once __DIR__ . '/../config/AbstractMapper.php';
require_once __DIR__ . '/../clases/Turno.php';

class TurnoDAL extends AbstractMapper {
    protected string $tabla = 'turnos';

    // Obtener todos los turnos
public function findAllTurnos(): array {
    $this->setConsulta("SELECT * FROM turnos");
    return $this->FindAll();
}

    // Mapear fila a objeto Turno
    protected function doLoad($columna): Turno {
        return new Turno(
            (int)$columna['id'],
            $columna['nombre'] ?? ''
        );
    }
}
