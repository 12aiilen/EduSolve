<?php

//Valen necesito qe arregles los enlaces de tu dashboard.php
//tambien que coordines los estilos con las demas paginas.
//por ahora solo agarran el modulo de Brisa y mio, pq el de vero necesita adaptarse
//mas que nada pq vos y yo necesitamos cosas de ella, por ejemplo nombre de estudiante, cantidad etc.
// y necesito q cada cosa que haga tu profesor, qsy agregar calificacion
//impacte en la base de datos, no me deja entrar a 'agregar calificaciones' pq no estan bien enlazado los modulos
//supongo q tenes q elegir un estudiante (de los cargados en la base de dato o los que yo te asigne) qsp ponele q pepito
// para ponerle una calificacion
//entonces qsy yo creo un alumno (pepito), te lo envio a vos, vos lo seleccionas y le asignas una nota


//esa nota cuando yo consulte en la base de datos, tendria q aparecer que pepito tiene la nota que vos le pusiste.
//todo lo q hagas proba q dps nos funcione a brisa y a mi el login porfa
//la contraseña mia y usuario es : carla9@gmail.com contraseña:1234
//la de brisa: brisa@gmail.com contraseña:1234
//si no llega a andar avisa porfi

require_once __DIR__."/../clases/Profesor.php";
require_once __DIR__."/../config/AbstractMapper.php";

class ProfesorDAL extends AbstractMapper
{
    public function getAllProfesores(): array {
        $sql = "SELECT * FROM usuarios WHERE idTiposUsuarios = 4";
        $this->setConsulta($sql);
        return $this->FindAll(); // devuelve array de objetos Usuario o array asociativo
    }

    public function getProfesorById($idUsuario) {
        $sql = "SELECT * FROM usuarios WHERE idUsuarios = $idUsuario AND idTiposUsuarios = 4";
        $this->setConsulta($sql);
        return $this->Find();
    }

    public function getProfesorByDni($dni)
    {
        $consulta = "SELECT * FROM profesores WHERE dni = '$dni'";
        $this->setConsulta($consulta);
        $resultado = $this->Find();
        return $resultado;
    }

    public function getProfesorByUsuarioId($usuarioId)
    {
        $consulta = "SELECT * FROM profesores WHERE usuario_id = '$usuarioId'";
        $this->setConsulta($consulta);
        $resultado = $this->Find();
        return $resultado;
    }

    public function InsertarProfesor($dni, $nombre, $apellido, $email, $usuario_id)
    {
        $sql = "INSERT INTO profesores (dni, nombre, apellido, email, usuario_id, activo, created_at) 
                VALUES ('$dni', '$nombre', '$apellido', '$email', $usuario_id, 1, NOW())";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    public function UpdateProfesor($profesor)
    {
        $consulta = "UPDATE profesores 
        SET 
            dni='" . $profesor->getDni() . "',
            nombre='" . $profesor->getNombre() . "',
            apellido='" . $profesor->getApellido() . "',
            email='" . $profesor->getEmail() . "',
            telefono='" . $profesor->getTelefono() . "'
        WHERE idProfesores='" . $profesor->getId() . "'";

        $this->setConsulta($consulta);
        $id = $this->Execute();
        return $id;
    }

    public function deleteProfesor($id)
    {
        $consulta = "UPDATE profesores SET activo = 0 WHERE idProfesores = '$id'";
        $this->setConsulta($consulta);
        $resultado = $this->Execute();
        return $resultado;
    }

    public function EliminarPorUsuarioId($usuario_id)
    {
        $sql = "DELETE FROM profesores WHERE usuario_id = $usuario_id";
        $this->setConsulta($sql);
        return $this->Execute();
    }

    // Métodos pendientes (puedes implementarlos después)
    public function asignarCursoAProfesor(){
        // Implementar luego
    }

    public function getCursosDelProfesor(){
        // Implementar luego
    }

    public function eliminarCursoDelProfesor(){
        // Implementar luego
    }

public function doLoad($columna)
{
    $id = (int) $columna['idUsuarios'];
    $dni = (string) $columna['DNI'];
    $nombre = (string) $columna['Nombre'];
    $apellido = (string) $columna['Apellido'];
    $email = isset($columna['Email']) ? (string)$columna['Email'] : null;
    $telefono = isset($columna['Telefono']) ? (string)$columna['Telefono'] : null;
    $activo = true; // no tienes columna activa en usuarios

    $profesor = new Profesor(
        $id,
        $dni,
        $nombre,
        $apellido,
        $email,
        $telefono,
        $activo,
        null // no hay usuario_id extra
    );

    return $profesor;
}

}
?>