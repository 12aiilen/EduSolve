<?php
require_once __DIR__ . '/../config/AbstractMapper.php';

class MaterialDidacticoDAL extends AbstractMapper {
    protected function doLoad($columna) {
        return $columna;
    }

    public function insertarMaterial($data) {
        $titulo = $this->conexion->real_escape_string($data['titulo']);
        $descripcion = $this->conexion->real_escape_string($data['descripcion']);
        $nombre_archivo = $this->conexion->real_escape_string($data['nombre_archivo']);
        $ruta_archivo = $this->conexion->real_escape_string($data['ruta_archivo']);
        $profesor_id = intval($data['profesor_id']);
        $materia_id = intval($data['materia_id']);

        $sql = "INSERT INTO material_didactico 
                (titulo, descripcion, nombre_archivo, ruta_archivo, profesor_id, materia_id, fecha_subida)
                VALUES ('$titulo', '$descripcion', '$nombre_archivo', '$ruta_archivo', $profesor_id, $materia_id, NOW())";
        
        $this->setConsulta($sql);
        return $this->Execute();
    }
}
