<?php
require_once(__DIR__."../../clases/Tutor.php");
require_once(__DIR__ . "/../config/AbstractMapper.php");

class TutorDAL extends AbstractMapper
{

    public function InsertarTutor($tutor)
    {
        $consulta= "INSERT INTO tutores (Nombre,Apellido,Dni,Email,Telefono) VALUES
        ('".$tutor->getNombre()."',
        '".$tutor->getApellido()."',
        '".$tutor->getDni()."',
        '".$tutor->getEmail()."',
        '".$tutor->getTelefono()."'
        )";
        $this->setConsulta($consulta);
        $id= $this->Execute();
        return $id;
    }
    public function findTutorByIdAlumno($idTutor)
    {
        $consulta= "SELECT * FROM tutores WHERE idTutores= '$idTutor'";
        $this->setConsulta($consulta);
        $resultado= $this->Find();
        if($resultado == null)
        {
            return null;
        }
        return $resultado;
    }

    public function findAllTutor()
    {
        
        $consulta= "SELECT * FROM tutores";
        $this->setConsulta($consulta);
        $resultado= $this->FindAll();
        return $resultado;
    }


    public function UpdateTutor($tutor)
    {
        $consulta="UPDATE tutores 
        SET Nombre='" . $tutor->getNombre() . "',
            Apellido='".$tutor->getApellido()."',
            Dni='".$tutor->getDni()."',
            Email='".$tutor->getEmail()."',
            Telefono='".$tutor->getTelefono()."'
            WHERE idTutores='" . $tutor->getId() . "';        
        ";
        $this->setConsulta($consulta);
        $id= $this->Execute();
        return $id;
    }



    public function doLoad($columna)
    {
        $id= (int) $columna["idTutores"];
        $nombre= (string) $columna["Nombre"];
        $apellido= (string) $columna["Apellido"];
        $dni= (string) $columna["Dni"];
        $email= (string) $columna["Email"];
        $telefono= (string) $columna["Telefono"];
        
        $tutor= new Tutor(
            $id,
            $nombre,
            $apellido,
            $dni,
            $email,
            $telefono
        );

        return $tutor;

    }
}