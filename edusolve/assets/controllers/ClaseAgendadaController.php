<?php
require_once __DIR__ . '/../bll/ClaseAgendadaBLL.php';
require_once __DIR__ . '/../clases/ClaseAgendada.php';

class ClaseAgendadaController {
    private ClaseAgendadaBLL $bll;

    public function __construct() {
        $this->bll = new ClaseAgendadaBLL();
    }

    public function procesarFormulario(array $post, int $profesor_id): array {
        if (empty($post['fecha']) || empty($post['hora_inicio']) || empty($post['hora_fin']) ||
            empty($post['materia_id']) || empty($post['curso_id'])) {
            return ["Error: faltan datos obligatorios.", "danger"];
        }

        $clase = new ClaseAgendada(
            $profesor_id,
            (int)$post['materia_id'],
            (int)$post['curso_id'],
            $post['fecha'],
            $post['hora_inicio'],
            $post['hora_fin'],
            $post['descripcion'] ?? ''
        );

        $resultado = $this->bll->agendarClase($clase);

        return $resultado
            ? ["Clase agendada con éxito.", "success"]
            : ["Error al guardar la clase.", "danger"];
    }
}
?>
