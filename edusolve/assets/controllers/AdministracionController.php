<?php
require_once __DIR__ . '/../dal/administracionDAL.php';

class AdministracionController
{
    private $dal;

    public function __construct()
    {
        $this->dal = new AdministracionDAL();
    }

    public function obtenerTodosLosAdmins()
    {
        return $this->dal->getAll();
    }

    public function obtenerAdminPorId($id)
    {
        return $this->dal->getById($id);
    }

    public function crearAdmin($usuario_id, $fecha_alta)
    {
        return $this->dal->insertar($usuario_id, $fecha_alta);
    }

    public function actualizarAdmin($id, $usuario_id, $fecha_alta)
    {
        return $this->dal->actualizar($id, $usuario_id, $fecha_alta);
    }

    public function eliminarAdmin($id)
    {
        return $this->dal->eliminar($id);
    }

    public function obtenerInfoCompletaAdmin($id)
    {
        $admin = $this->dal->getById($id);
        if ($admin) {
            return $admin->getInfoCompleta();
        }
        return "Administrador no encontrado";
    }
}