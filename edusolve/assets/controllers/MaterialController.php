<?php
require_once __DIR__ . '/../bll/MaterialBLL.php';

class MaterialController {
    private $materialBLL;

    public function __construct() {
        $this->materialBLL = new MaterialBLL();
    }

    public function manejarSubida($post, $files, $profesor_id) {
        $titulo = $post['titulo'] ?? '';
        $descripcion = $post['descripcion'] ?? '';
        $materia_id = $post['materia_id'] ?? '';
        $archivo = $files['archivo'] ?? null;

        return $this->materialBLL->subirMaterial($titulo, $descripcion, $archivo, $profesor_id, $materia_id);
    }
}
