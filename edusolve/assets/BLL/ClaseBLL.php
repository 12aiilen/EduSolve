<?php
require_once __DIR__ . '/../dal/ClaseDAL.php';

class ClaseBLL {
    private $dal;

    public function __construct() {
        $this->dal = new ClaseDAL();
    }

    public function agendarClase(Clase $clase) {
        // Validaciones de negocio
        if (empty($clase->getFecha()) || empty($clase->getHoraInicio()) ||
            empty($clase->getHoraFin()) || empty($clase->getMateriaId()) ||
            empty($clase->getCursoId())) {
            return ['success' => false, 'message' => 'Faltan datos obligatorios.'];
        }

        $resultado = $this->dal->insertarClase($clase);
        if ($resultado) {
            return ['success' => true, 'message' => 'Clase agendada correctamente.'];
        } else {
            return ['success' => false, 'message' => 'Error al agendar la clase.'];
        }
    }

    public function obtenerMaterias($profesor_id) {
        return $this->dal->obtenerMateriasPorProfesor($profesor_id);
    }
}
?>
