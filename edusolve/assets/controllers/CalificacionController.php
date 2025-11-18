<?php
// require_once _DIR_."/../BLL/CalificacionBLL.php";
require_once __DIR__ .'/../BLL/Calificacion.php';

class CalificacionController
{
    private $calificacionBLL;

    public function __construct()
    {
        $this->calificacionBLL = new CalificacionBLL();
    }

    public function listarCalificaciones()
    {
        try {
            return $this->calificacionBLL->obtenerTodasCalificaciones();
        } catch (Exception $e) {
            $this->manejarError($e);
            return [];
        }
    }

    public function obtenerCalificacion($id)
    {
        try {
            return $this->calificacionBLL->obtenerCalificacionPorId($id);
        } catch (Exception $e) {
            $this->manejarError($e);
            return null;
        }
    }

    public function agregarCalificacion($estudiante_id, $materia_id, $profesor_id, $calificacion, $tipo_evaluacion_id, $fecha, $observaciones = '')
    {
        try {
            $resultado = $this->calificacionBLL->crearCalificacion(
                $estudiante_id, 
                $materia_id, 
                $profesor_id, 
                $calificacion, 
                $tipo_evaluacion_id, 
                $fecha, 
                $observaciones
            );

            if ($resultado) {
                $this->manejarExito("Calificación agregada correctamente");
                return true;
            } else {
                throw new Exception("No se pudo agregar la calificación");
            }
        } catch (Exception $e) {
            $this->manejarError($e);
            return false;
        }
    }

    public function editarCalificacion($id, $nuevaCalificacion)
    {
        try {
            $calificacion = $this->calificacionBLL->obtenerCalificacionPorId($id);
            if (!$calificacion) {
                throw new Exception("Calificación no encontrada");
            }

            $calificacion->setCalificacion($nuevaCalificacion);
            $resultado = $this->calificacionBLL->actualizarCalificacion($calificacion);

            if ($resultado) {
                $this->manejarExito("Calificación actualizada correctamente");
                return true;
            } else {
                throw new Exception("No se pudo actualizar la calificación");
            }
        } catch (Exception $e) {
            $this->manejarError($e);
            return false;
        }
    }

    public function eliminarCalificacion($id)
    {
        try {
            $resultado = $this->calificacionBLL->eliminarCalificacion($id);

            if ($resultado) {
                $this->manejarExito("Calificación eliminada correctamente");
                return true;
            } else {
                throw new Exception("No se pudo eliminar la calificación");
            }
        } catch (Exception $e) {
            $this->manejarError($e);
            return false;
        }
    }

    public function obtenerEstudiantes()
    {
        try {
            return $this->calificacionBLL->obtenerEstudiantes();
        } catch (Exception $e) {
            $this->manejarError($e);
            return [];
        }
    }

public function obtenerMateriasAsignadas($profesor_id) {
    $sql = "SELECT DISTINCT m.id, m.nombre 
            FROM materias m 
            JOIN asignacion_profesores ap ON m.id = ap.materia_id 
            WHERE ap.usuarios_id = $profesor_id 
            ORDER BY m.nombre";
    
    $mapper = new class extends AbstractMapper { 
        protected function doLoad($columna) { return $columna; } 
    };
    $mapper->setConsulta($sql);
    return $mapper->FindAll();
}

    public function obtenerProfesores()
    {
        try {
            return $this->calificacionBLL->obtenerProfesores();
        } catch (Exception $e) {
            $this->manejarError($e);
            return [];
        }
    }

    public function obtenerTiposEvaluacion()
    {
        try {
            return $this->calificacionBLL->obtenerTiposEvaluacion();
        } catch (Exception $e) {
            $this->manejarError($e);
            return [];
        }
    }

    public function obtenerCalificacionesCompletas()
    {
        try {
            return $this->calificacionBLL->obtenerCalificacionesCompletas();
        } catch (Exception $e) {
            $this->manejarError($e);
            return [];
        }
    }

    public function obtenerPromedioEstudiante($estudiante_id)
    {
        try {
            return $this->calificacionBLL->obtenerPromedioPorEstudiante($estudiante_id);
        } catch (Exception $e) {
            $this->manejarError($e);
            return 0;
        }
    }

    private function manejarError(Exception $e)
    {
        // Aquí puedes loggear el error, mostrar mensaje al usuario, etc.
        error_log("Error en CalificacionController: " . $e->getMessage());
        
        // Para desarrollo, puedes mostrar el error directamente
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['error'] = $e->getMessage();
    }

    private function manejarExito($mensaje)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['success'] = $mensaje;
    }

    public function buscarCalificaciones($termino)
    {
        try {
            $calificaciones = $this->calificacionBLL->obtenerCalificacionesCompletas();
            
            return array_filter($calificaciones, function($calificacion) use ($termino) {
                return stripos($calificacion['estudiante_nombre'] . ' ' . $calificacion['estudiante_apellido'], $termino) !== false ||
                       stripos($calificacion['materia_nombre'], $termino) !== false ||
                       stripos($calificacion['tipo_evaluacion_nombre'], $termino) !== false ||
                       stripos($calificacion['calificacion'], $termino) !== false;
            });
        } catch (Exception $e) {
            $this->manejarError($e);
            return [];
        }
    }
}
?>