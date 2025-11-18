<?php
class ClaseAgendada {
    private int $id;
    private int $profesor_id;
    private int $materia_id;
    private int $curso_id;
    private string $fecha;
    private string $hora_inicio;
    private string $hora_fin;
    private string $descripcion;
    private string $estado;

    public function __construct(
        int $profesor_id,
        int $materia_id,
        int $curso_id,
        string $fecha,
        string $hora_inicio,
        string $hora_fin,
        string $descripcion = '',
        string $estado = 'Pendiente',
        int $id = 0
    ) {
        $this->id = $id;
        $this->profesor_id = $profesor_id;
        $this->materia_id = $materia_id;
        $this->curso_id = $curso_id;
        $this->fecha = $fecha;
        $this->hora_inicio = $hora_inicio;
        $this->hora_fin = $hora_fin;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getProfesorId(): int { return $this->profesor_id; }
    public function getMateriaId(): int { return $this->materia_id; }
    public function getCursoId(): int { return $this->curso_id; }
    public function getFecha(): string { return $this->fecha; }
    public function getHoraInicio(): string { return $this->hora_inicio; }
    public function getHoraFin(): string { return $this->hora_fin; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getEstado(): string { return $this->estado; }

    // Setters
    public function setEstado(string $estado): void { $this->estado = $estado; }
}
?>
