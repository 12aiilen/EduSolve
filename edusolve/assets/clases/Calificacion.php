<?php
class Calificacion {
    private $id;
    private $estudiante_id;
    private $materia_id;
    private $profesor_id;
    private $calificacion;
    private $tipo_evaluacion_id;
    private $fecha;
    private $observaciones;

    public function __construct($id, $estudiante_id, $materia_id, $profesor_id, $calificacion, $tipo_evaluacion_id = null, $fecha = null, $observaciones = null) {
        $this->id = $id;
        $this->estudiante_id = $estudiante_id;
        $this->materia_id = $materia_id;
        $this->profesor_id = $profesor_id;
        $this->calificacion = $calificacion;
        $this->tipo_evaluacion_id = $tipo_evaluacion_id;
        $this->fecha = $fecha;
        $this->observaciones = $observaciones;
    }

    public function getId() { return $this->id; }
    public function getEstudianteId() { return $this->estudiante_id; }
    public function getMateriaId() { return $this->materia_id; }
    public function getProfesorId() { return $this->profesor_id; }
    public function getCalificacion() { return $this->calificacion; }
    public function getTipoEvaluacionId() { return $this->tipo_evaluacion_id; }
    public function getFecha() { return $this->fecha; }
    public function getObservaciones() { return $this->observaciones; }

    public function setCalificacion($calificacion) {
        $this->calificacion = $calificacion;
    }
}
?>
