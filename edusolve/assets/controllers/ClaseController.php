<?php
require_once __DIR__ . '/../bll/ClaseBLL.php';

class ClaseController {
    private $bll;

    public function __construct() {
        $this->bll = new ClaseBLL();
    }

    public function procesarAgendamiento($data, $profesor_id) {
        $clase = new Clase(
            null,
            $profesor_id,
            $data['materia_id'] ?? null,
            $data['curso_id'] ?? null,
            $data['fecha'] ?? '',
            $data['hora_inicio'] ?? '',
            $data['hora_fin'] ?? '',
            $data['descripcion'] ?? '',
            'Pendiente'
        );
        return $this->bll->agendarClase($clase);
    }

    public function listarMaterias($profesor_id) {
        return $this->bll->obtenerMaterias($profesor_id);
    }
}
?>
