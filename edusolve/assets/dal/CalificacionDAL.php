<?php
require_once __DIR__."/../clases/Calificacion.php";
require_once __DIR__."/../config/AbstractMapper.php";

class CalificacionDAL extends AbstractMapper
{
    public function getAllCalificaciones(): array
    {
        $consulta = "SELECT * FROM calificaciones";
        $this->setConsulta($consulta);
        $lista = $this->FindAll();
        return $lista;
    }

    public function getCalificacionById($idCalificacion)
    {
        $consulta = "SELECT * FROM calificaciones WHERE id = '$idCalificacion'";
        $this->setConsulta($consulta);
        $resultado = $this->Find();
        return $resultado;
    }

    public function InsertarCalificacion($estudiante_id, $materia_id, $profesor_id, $calificacion, $tipo_evaluacion_id, $fecha, $observaciones = '')
    {
        $sql = "INSERT INTO calificaciones 
                (estudiante_id, materia_id, profesor_id, calificacion, tipo_evaluacion_id, fecha, observaciones) 
                VALUES ($estudiante_id, $materia_id, $profesor_id, $calificacion, $tipo_evaluacion_id, '$fecha', '$observaciones')";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    public function UpdateCalificacion($calificacion)
    {
        $consulta = "UPDATE calificaciones 
        SET 
            estudiante_id = " . $calificacion->getEstudianteId() . ",
            materia_id = " . $calificacion->getMateriaId() . ",
            profesor_id = " . $calificacion->getProfesorId() . ",
            calificacion = " . $calificacion->getCalificacion() . ",
            tipo_evaluacion_id = " . $calificacion->getTipoEvaluacionId() . ",
            fecha = '" . $calificacion->getFecha() . "',
            observaciones = '" . $calificacion->getObservaciones() . "'
        WHERE id = " . $calificacion->getId();

        $this->setConsulta($consulta);
        return $this->Execute();
    }

    public function deleteCalificacion($id)
    {
        $consulta = "DELETE FROM calificaciones WHERE id = $id";
        $this->setConsulta($consulta);
        $resultado = $this->Execute();
        return $resultado;
    }

    public function getEstudiantes()
    {
        $consulta = "SELECT 
                        a.idAlumnos as id,
                        a.Nombre, 
                        a.Apellido,
                        c.Año as anio,
                        c.Division as division
                    FROM alumnos a
                    INNER JOIN cursos c ON a.idCursos = c.idCursos
                    WHERE a.idAlumnos IS NOT NULL
                    ORDER BY a.Apellido, a.Nombre";
        
        return $this->ejecutarConsultaSimple($consulta);
    }

    public function getEstudianteById($idEstudiante)
    {
        $consulta = "SELECT 
                        a.idAlumnos as id,
                        a.Nombre, 
                        a.Apellido,
                        c.Año as anio,
                        c.Division as division
                    FROM alumnos a
                    INNER JOIN cursos c ON a.idCursos = c.idCursos
                    WHERE a.idAlumnos = '$idEstudiante'";
        
        return $this->ejecutarConsultaSimple($consulta)[0] ?? null;
    }

    public function getProfesores()
    {
        $consulta = "SELECT 
                        u.idUsuarios as id,
                        u.Nombre, 
                        u.Apellido,
                        u.Email
                    FROM usuarios u
                    WHERE u.idTiposUsuarios = 4
                    ORDER BY u.Apellido, u.Nombre";
        
        return $this->ejecutarConsultaSimple($consulta);
    }

    public function getProfesorById($idProfesor)
    {
        $consulta = "SELECT 
                        u.idUsuarios as id,
                        u.Nombre, 
                        u.Apellido,
                        u.Email
                    FROM usuarios u
                    WHERE u.idTiposUsuarios = 4 AND u.idUsuarios = '$idProfesor'";
        
        return $this->ejecutarConsultaSimple($consulta)[0] ?? null;
    }

    // Este método NO debe usar FindAll() porque tiene estructura diferente
    public function getCalificacionesCompletas()
    {
        $consulta = "SELECT 
                        c.id,
                        c.estudiante_id,
                        c.materia_id,
                        c.profesor_id, 
                        c.calificacion,
                        c.tipo_evaluacion_id,
                        c.fecha,
                        c.observaciones,
                        a.Nombre as estudiante_nombre,
                        a.Apellido as estudiante_apellido,
                        u.Nombre as profesor_nombre,
                        u.Apellido as profesor_apellido,
                        m.nombre as materia_nombre,
                        te.nombre as tipo_evaluacion_nombre
                    FROM calificaciones c
                    LEFT JOIN alumnos a ON c.estudiante_id = a.idAlumnos
                    LEFT JOIN usuarios u ON c.profesor_id = u.idUsuarios
                    LEFT JOIN materias m ON c.materia_id = m.id
                    LEFT JOIN tipos_evaluacion te ON c.tipo_evaluacion_id = te.id
                    ORDER BY a.Apellido, a.Nombre";
        
        return $this->ejecutarConsultaSimple($consulta);
    }

    public function getMaterias()
    {
        $consulta = "SELECT 
                        id as id,
                        nombre,
                        descripcion,
                        horas_semanales,
                        anio
                    FROM materias 
                    ORDER BY nombre";
        
        return $this->ejecutarConsultaSimple($consulta);
    }

    public function getTiposEvaluacion()
    {
        $consulta = "SELECT 
                        id as id,
                        nombre
                    FROM tipos_evaluacion 
                    ORDER BY nombre";
        
        return $this->ejecutarConsultaSimple($consulta);
    }

        private function ejecutarConsultaSimple($consulta)
        {
            $resultado = $this->conexion->query($consulta);

            if (!$resultado) {
                die("Error en la consulta: " . $this->conexion->error);
            }

            $datos = [];
            while ($fila = $resultado->fetch_assoc()) {
                $datos[] = $fila;
            }

            $resultado->free();
            return $datos;
        }


    public function doLoad($columna)
    {
        // Verificar que las claves existan antes de acceder a ellas
        if (!isset($columna['id']) || !isset($columna['estudiante_id']) || 
            !isset($columna['materia_id']) || !isset($columna['profesor_id']) || 
            !isset($columna['calificacion'])) {
            return null; // O manejar el error apropiadamente
        }

        $id = (int) $columna['id'];
        $estudiante_id = (int) $columna['estudiante_id'];
        $materia_id = (int) $columna['materia_id'];
        $profesor_id = (int) $columna['profesor_id'];
        $calificacion = (float) $columna['calificacion'];
        $tipo_evaluacion_id = isset($columna['tipo_evaluacion_id']) ? (int) $columna['tipo_evaluacion_id'] : null;
        $fecha = isset($columna['fecha']) ? (string) $columna['fecha'] : null;
        $observaciones = isset($columna['observaciones']) ? (string) $columna['observaciones'] : null;

        // Asegúrate de que tu clase Calificacion tenga este constructor
        $calificacionObj = new Calificacion(
            $id,
            $estudiante_id,
            $materia_id,
            $profesor_id,
            $calificacion,
            $tipo_evaluacion_id,
            $fecha,
            $observaciones
        );
        return $calificacionObj;
    }
}
?>