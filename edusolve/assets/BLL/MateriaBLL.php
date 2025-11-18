<?php
require_once __DIR__ . '/../DAL/MateriaDAL.php';

class MateriaBLL {

    private $dal;

    public function __construct() {
        $this->dal = new MateriaDAL();
    }

public function obtenerPorIds(array $ids): array {
    $materiaDAL = new MateriaDAL();
    return $materiaDAL->getByIds($ids);
}

    public function eliminarMateria($id) {
        return $this->dal->eliminar($id);
    }

    public function actualizarMateria($id, $nombre, $descripcion, $horas_semanales = null, $anio = null) {
        if (empty($id) || empty($nombre)) {
            throw new Exception("El ID y el nombre son obligatorios");
        }
        return $this->dal->actualizar($id, $nombre, $descripcion, $horas_semanales, $anio);
    }

    // 🔹 NUEVO: insertar materia y devolver ID
    public function insertar($nombre, $descripcion, $horas_semanales, $anio) {
        if (empty($nombre)) {
            throw new Exception("El nombre de la materia es obligatorio");
        }
        return $this->dal->insertar($nombre, $descripcion, $horas_semanales, $anio);
    }


     public function subirMaterial($titulo, $descripcion, $archivo, $profesor_id, $materia_id) {
        $uploadDir = __DIR__ . '/../../pages/teacher/uploads/material/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
            return ["error" => "Debe seleccionar un archivo válido."];
        }

        $nombreArchivo = basename($archivo['name']);
        $rutaFisica = $uploadDir . $nombreArchivo;
        $rutaRelativa = "uploads/material/" . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaFisica)) {
            return ["error" => "Error al mover el archivo al servidor."];
        }

        $ok = $this->materialDAL->insertarMaterial($titulo, $descripcion, $nombreArchivo, $rutaRelativa, $profesor_id, $materia_id);

        if (!$ok) {
            unlink($rutaFisica);
            return ["error" => "No se pudo registrar el material en la base de datos."];
        }

        return ["success" => "Material subido correctamente."];
    }

    public function listarMaterialesProfesor($profesor_id) {
        return $this->materialDAL->obtenerPorProfesor($profesor_id);
    }
        public function obtenerMateriasAsignadas(int $profesor_id): array {
        return $this->dal->obtenerMateriasAsignadas($profesor_id);
    }


}
?>
