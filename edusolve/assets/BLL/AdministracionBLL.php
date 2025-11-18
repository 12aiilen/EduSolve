<?php
require_once __DIR__ . '/../dal/administracionDAL.php';

class AdministracionBLL {
    private AdministracionDAL $dal;

    public function __construct() {
        $this->dal = new AdministracionDAL();
    }

    public function obtenerTodos(): array {
        return $this->dal->getAll();
    }

    public function obtenerPorId($id) {
        return $this->dal->getById($id);
    }

    public function crear($usuario_id, $fecha_alta) {
        return $this->dal->insertar($usuario_id, $fecha_alta);
    }

    public function actualizar($id, $usuario_id, $fecha_alta) {
        return $this->dal->actualizar($id, $usuario_id, $fecha_alta);
    }

    public function eliminar($id) {
        return $this->dal->eliminar($id);
    }
}
?>