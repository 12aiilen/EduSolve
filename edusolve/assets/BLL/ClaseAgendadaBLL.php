<?php
require_once __DIR__ . '/../dal/ClaseAgendadaDAL.php';

class ClaseAgendadaBLL {
    private ClaseAgendadaDAL $dal;

    public function __construct() {
        $this->dal = new ClaseAgendadaDAL();
    }

    public function agendarClase(ClaseAgendada $clase): bool {
        return $this->dal->insertar($clase);
    }

    public function listarPorProfesor(int $profesor_id): array {
        return $this->dal->obtenerPorProfesor($profesor_id);
    }
}
?>
