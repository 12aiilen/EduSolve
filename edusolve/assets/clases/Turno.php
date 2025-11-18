<?php
class Turno {
    private int $idTurno;
    private string $Nombre;

    public function __construct(int $idTurno, string $Nombre) {
        $this->idTurno = $idTurno;
        $this->Nombre = $Nombre;
    }

    public function getIdTurno(): int {
        return $this->idTurno;
    }

    public function getNombre(): string {
        return $this->Nombre;
    }
}
