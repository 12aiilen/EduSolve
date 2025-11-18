<?php
require_once __DIR__ . '/../config/AbstractMapper.php';
require_once __DIR__ . '/../clases/Materia.php';

class MateriaDAL extends AbstractMapper {

    protected string $tabla = 'materias';

    public function __construct() {
        parent::__construct();
    }

    protected function doLoad($columna) {
        return [
            'id' => $columna['id'] ?? null,
            'nombre' => $columna['nombre'] ?? null,
            'descripcion' => $columna['descripcion'] ?? null,
            'horas_semanales' => $columna['horas_semanales'] ?? 0,
            'anio' => $columna['anio'] ?? 1
        ];
    }

    public function insertar(string $nombre, string $descripcion, int $horas_semanales, int $anio): int {
        $sql = "INSERT INTO materias (nombre, descripcion, horas_semanales, anio)
                VALUES ('$nombre', '$descripcion', $horas_semanales, $anio)";
        
        $conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->basededatos);
        if ($conexion->connect_error) {
            die('Error de conexión: ' . $conexion->connect_error);
        }

        $conexion->query($sql);
        $id = $conexion->insert_id;
        $conexion->close();

        return (int)$id;
    }

    // 🔹 Obtener materias por un conjunto de IDs
public function getByIds(array|string $ids): array {
    // Si viene un array, convertirlo a "1,2,3"
    if (is_array($ids)) {
        $ids = implode(',', array_map('intval', $ids));
    }

    if (empty($ids)) {
        return [];
    }

    $sql = "SELECT * FROM materias WHERE id IN ($ids)";
    $this->setConsulta($sql);
    $filas = $this->FindAll();

    $materias = [];
    foreach ($filas as $fila) {
        $materias[] = new Materia(
            (int)$fila['id'],
            (string)$fila['nombre'],
            (string)$fila['descripcion'],
            (int)$fila['horas_semanales'],
            (int)$fila['anio']
        );
    }

    return $materias;
}


    // 🔹 Obtener TODAS las materias
    public function obtenerTodas(): array {
        $sql = "SELECT * FROM materias";
        $this->setConsulta($sql);
        $filas = $this->FindAll();

        $materias = [];
        foreach ($filas as $fila) {
            $materias[] = new Materia(
                (int)$fila['id'],
                (string)$fila['nombre'],
                (string)$fila['descripcion'],
                (int)$fila['horas_semanales'],
                (int)$fila['anio']
            );
        }

        return $materias;
    }

    // 🔹 Actualizar materia
    public function actualizar(int $id, string $nombre, string $descripcion, ?int $horas_semanales = null, ?int $anio = null): bool {
        $sql = "UPDATE materias SET 
                nombre='$nombre', 
                descripcion='$descripcion', 
                horas_semanales=" . ($horas_semanales ?? 0) . ", 
                anio=" . ($anio ?? 1) . "
                WHERE id=$id";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    // 🔹 Eliminar materia
    public function eliminar(int $id): bool {
        $sql = "DELETE FROM materias WHERE id=$id";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    // 🔹 Obtener nombre de materia por ID
    public function obtenerNombrePorId(int $id): ?string {
        $sql = "SELECT nombre FROM materias WHERE id = $id";
        $this->setConsulta($sql);
        $resultado = $this->Find();
        return $resultado['nombre'] ?? null;
    }

    public function insertarMaterial($titulo, $descripcion, $nombreArchivo, $rutaRelativa, $profesor_id, $materia_id) {
        $titulo = $this->conexion->real_escape_string($titulo);
        $descripcion = $this->conexion->real_escape_string($descripcion);

        $sql = "INSERT INTO material_didactico 
                (titulo, descripcion, nombre_archivo, ruta_archivo, profesor_id, materia_id, fecha_subida)
                VALUES ('$titulo', '$descripcion', '$nombreArchivo', '$rutaRelativa', $profesor_id, $materia_id, NOW())";

        $this->setConsulta($sql);
        return $this->Execute();
    }

    public function obtenerPorProfesor($profesor_id) {
        $sql = "SELECT * FROM material_didactico WHERE profesor_id = $profesor_id ORDER BY fecha_subida DESC";
        $this->setConsulta($sql);
        return $this->FindAll();
    }

    // 🔹 Obtener materias asignadas a un profesor
public function obtenerMateriasAsignadas(int $profesor_id): array {
    $sql = "SELECT m.* 
            FROM materias m
            INNER JOIN asignacion_profesores apm ON m.id = apm.materia_id
            WHERE apm.id = $profesor_id";
    
    $this->setConsulta($sql);
    $filas = $this->FindAll();

    $materias = [];
    foreach ($filas as $fila) {
        $materias[] = new Materia(
            (int)$fila['id'],
            (string)$fila['nombre'],
            (string)$fila['descripcion'],
            (int)$fila['horas_semanales'],
            (int)$fila['anio']
        );
    }

    return $materias;
}

public function obtenerCursosAsignados(int $profesor_id): array {
    $sql = "SELECT DISTINCT c.id AS id_curso, c.Año, c.Division
            FROM cursos c
            INNER JOIN asignacion_profesores ap ON c.id = ap.curso_id
            WHERE ap.usuarios_id = $profesor_id
            ORDER BY c.Año, c.Division";
    
    $this->setConsulta($sql);
    return $this->FindAll();
}


}


?>
