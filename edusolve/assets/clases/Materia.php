<?php
class Materia {
    private int $id;
    private string $nombre;
    private string $descripcion;
    private int $horas_semanales;
    private int $anio;

    public function __construct(int $id, string $nombre, string $descripcion, int $horas_semanales, int $anio){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->horas_semanales = $horas_semanales;
        $this->anio = $anio;
    }

    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getHorasSemanales(): int { return $this->horas_semanales; }
    public function getAnio(): int { return $this->anio; }
}
?>
