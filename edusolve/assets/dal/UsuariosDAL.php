<?php
// MODIFICADO: ESTE ARCHIVO FUE ADAPTADO DESDE EL PROYECTO DE MIS COMPAÑEROS PARA EDUSOLVE.
// MODIFICACIONES: TIPOS SEGURIZADOS, CONVERSIONES EXPLÍCITAS Y VALORES POR DEFECTO.

require_once __DIR__ . '/../clases/Usuario.php';
require_once __DIR__ . '/../config/AbstractMapper.php';

class UsuarioDAL extends AbstractMapper
{
    public function FindAllAsistencias(): array
    {
        $this->setConsulta("SELECT FechaAsistencia, ValorAsistencia FROM asistencias");
        return $this->FindAll();
    }

    public function UpdateUser($usuario)
    {
        $consulta = "UPDATE usuarios 
            SET DNI='" . $usuario->getDni() . "',
                Email='" . $usuario->getEmail() . "',
                Contrasena='" . $usuario->getContrasena() . "',
                Nombre='" . $usuario->getNombre() . "',
                Apellido='" . $usuario->getApellido() . "',
                idTiposUsuarios=" . (int)$usuario->getIdTiposUsuarios() . "
            WHERE idUsuarios=" . (int)$usuario->getId() . ";";   
        $this->setConsulta($consulta);
        return $this->Execute();
    }

    public function DeleteUser($id)
    {
        $consulta = "DELETE FROM usuarios WHERE idUsuarios = " . (int)$id;
        $this->setConsulta($consulta);
        return $this->Execute();
    }

    public function InsertarUsuario($usuario)
    {
        $consulta = "INSERT INTO usuarios(DNI,Email,Contrasena,Nombre,Apellido,idTiposUsuarios) VALUES
        ('" . $usuario->getDni() . "',
        '" . $usuario->getEmail() . "',
        '" . $usuario->getContrasena() . "',
        '" . $usuario->getNombre() . "',
        '" . $usuario->getApellido() . "',
        " . (int)$usuario->getIdTiposUsuarios() . ")";
        $this->setConsulta($consulta);
        return $this->Execute();
    }

    public function getUsuarioByEmail($email): ?Usuario
    {
        $consulta = "SELECT * FROM usuarios WHERE Email = '" . str_replace("'", "''", $email) . "' LIMIT 1";
        $this->setConsulta($consulta);
        $usuario = $this->Find();
        return $usuario instanceof Usuario ? $usuario : null;
    }

    public function getAllUsuarios(): array
    {
        $consulta = "SELECT * FROM usuarios";
        $this->setConsulta($consulta);
        return $this->FindAll();
    }

    public function getUsuarioByIdCurso($idUsuario)
    {
        $consulta = "SELECT * FROM usuarios WHERE idUsuarios= " . (int)$idUsuario;
        $this->setConsulta($consulta);
        return $this->FindAll();
    }

    public function getCursoById($idCurso)
    {
        $consulta = "SELECT * FROM usuarios WHERE idUsuarios= " . (int)$idCurso;
        $this->setConsulta($consulta);
        return $this->Find();
    }

    public function doLoad($columna)
    {
        // SEGURIZAR TIPOS Y ASIGNAR VALORES POR DEFECTO
        $idUsuarios = isset($columna['idUsuarios']) ? (int)$columna['idUsuarios'] : 0;
        $dni = isset($columna['DNI']) ? (string)$columna['DNI'] : '';
        $email = isset($columna['Email']) ? (string)$columna['Email'] : '';
        $contrasena = isset($columna['Contrasena']) ? (string)$columna['Contrasena'] : '';
        $nombre = isset($columna['Nombre']) ? (string)$columna['Nombre'] : '';
        $apellido = isset($columna['Apellido']) ? (string)$columna['Apellido'] : '';
        $idTiposUsuarios = isset($columna['idTiposUsuarios']) ? (int)$columna['idTiposUsuarios'] : 0;

        return new Usuario(
            $idUsuarios,
            $dni,
            $email,
            $contrasena,
            $nombre,
            $apellido,
            $idTiposUsuarios
        );
    }

    public function AuthUsuario(string $nombreUsuario, string $contrasena): ?Usuario
    {
        $nombreUsuario = str_replace("'", "''", $nombreUsuario);
        $consulta = "SELECT * FROM usuarios WHERE Nombre = '$nombreUsuario' OR Email = '$nombreUsuario' LIMIT 1";
        $this->setConsulta($consulta);

        $usuario = $this->Find();

        if ($usuario instanceof Usuario) {
            $hash = $usuario->getContrasena();

            // VERIFICAR CONTRASEÑA HASHEADA
            if (password_verify($contrasena, $hash)) {
                return $usuario;
            }

            // COMPATIBILIDAD TEMPORAL: TEXTO PLANO
            if ($contrasena === $hash) {
                return $usuario;
            }
        }

        return null;
    }
public function obtenerNombrePorId(int $idUsuario): ?string {
    $sql = "SELECT * FROM usuarios WHERE idUsuarios = $idUsuario";
    $this->setConsulta($sql);
    $usuario = $this->Find();

    if ($usuario) {
        // Si $usuario es un objeto de tipo Usuario
        return trim($usuario->getNombre() . ' ' . $usuario->getApellido());
    }

    return null; // Si no existe el usuario
}



public function obtenerPorTipoUsuario(int $tipoId): array
{
    $consulta = "SELECT * FROM usuarios WHERE idTiposUsuarios = " . (int)$tipoId;
    $this->setConsulta($consulta);
    return $this->FindAll();
}


public function obtenerTodas(): array
{
    $consulta = "SELECT * FROM materias";
    $this->setConsulta($consulta);
    return $this->FindAll();
}


}
?>
