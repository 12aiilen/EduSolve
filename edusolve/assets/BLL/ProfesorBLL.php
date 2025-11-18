<?php
require_once __DIR__ . '/../DAL/ProfesorDAL.php';
require_once __DIR__ . '/../clases/Profesor.php';

class ProfesorBLL
{
    private ProfesorDAL $profesorDAL;

    public function __construct()
    {
        $this->profesorDAL = new ProfesorDAL();
    }

    /**
     * Devuelve todos los profesores activos
     * @return Profesor[]
     */
    public function obtenerTodos(): array
    {
        // Usamos el método público del DAL
        return $this->profesorDAL->getAllProfesores();
    }

    /**
     * Obtener un profesor por su ID
     */
    public function obtenerPorId(int $id): ?Profesor
    {
        return $this->profesorDAL->getProfesorById($id);
    }
}
?>
