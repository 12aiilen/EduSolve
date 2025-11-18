<?php
class Clase {
    private $id;
    private $profesor_id;
    private $materia_id;
    private $curso_id;
    private $fecha;
    private $hora_inicio;
    private $hora_fin;
    private $descripcion;
    private $estado;

    public function __construct($id = null, $profesor_id = null, $materia_id = null, $curso_id = null,
                                $fecha = '', $hora_inicio = '', $hora_fin = '', $descripcion = '', $estado = 'Pendiente') {
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
    public function getId() { return $this->id; }
    public function getProfesorId() { return $this->profesor_id; }
    public function getMateriaId() { return $this->materia_id; }
    public function getCursoId() { return $this->curso_id; }
    public function getFecha() { return $this->fecha; }
    public function getHoraInicio() { return $this->hora_inicio; }
    public function getHoraFin() { return $this->hora_fin; }
    public function getDescripcion() { return $this->descripcion; }
    public function getEstado() { return $this->estado; }

    // Setters
    public function setProfesorId($v) { $this->profesor_id = $v; }
    public function setMateriaId($v) { $this->materia_id = $v; }
    public function setCursoId($v) { $this->curso_id = $v; }
    public function setFecha($v) { $this->fecha = $v; }
    public function setHoraInicio($v) { $this->hora_inicio = $v; }
    public function setHoraFin($v) { $this->hora_fin = $v; }
    public function setDescripcion($v) { $this->descripcion = $v; }
    public function setEstado($v) { $this->estado = $v; }
}
?>
