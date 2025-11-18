<?php

require_once __DIR__."/../clases/Curso.php";
require_once __DIR__."/../dal/CursoDAL.php";
require_once __DIR__."/../clases/Usuario.php";

class CursoBLL
{

    public static function getAllCursos(): array
    {
        $cursoDAL = new CursoDAL();
        $lista = $cursoDAL->getAllCursos();
        return $lista;
    }

// 🔹 Corregido para usar getIdUsuarios()
public function cursosAsignados(): array
{
    if (!isset($_SESSION['usuario'])) {
        return [];
    }

    $usuario = unserialize($_SESSION['usuario']);
    $idPreceptor = $usuario->getIdUsuarios(); // <-- CORREGIDO

    return $this->getCursosByIdPreceptor(idPreceptor: $idPreceptor);
}



public static function getCursosByIdPreceptor($idPreceptor): array
{
    $cursoDAL = new CursoDAL();
    return $cursoDAL->obtenerCursosDePreceptor($idPreceptor);
}

    


    
    public static function findCursoByIdAlumno($idAlumnoCurso): array
    {
        $cursoDAL = new CursoDAL();
        $resultado= $cursoDAL->findCursosByAlumno($idAlumnoCurso);
        return $resultado;
    }



    public static function getUsuarioByIdCurso($idCurso)
    {
        $cursoDAL = new CursoDAL();
        $resultado= $cursoDAL->getCursoById($idCurso);
        return $resultado;
    }



    public function findCursosById($idPreceptor): array
    {
        $usuario = new CursoDAL();

        $lista = $this->getCursosByIdPreceptor(idPreceptor: $idPreceptor);
        return $lista;
    }

    public function GrabarCurso($curso)
    {
        $cursoDAl= new CursoDAL();
        $resultado= $cursoDAl->InsertarCurso($curso);
        return $resultado;
    }

    public function UpdateCurso($curso)
    {
        $cursoDAL= new CursoDAL();
        $resultado= $cursoDAL->UpdateCurso($curso);
        return $resultado;
    }

    

    public function deleteCurso($idCurso)
    {
        $cursoDAL= new CursoDAL();
        $resultado= $cursoDAL->deleteCurso($idCurso);
        return $resultado;    
    }


public function obtenerCursosAsignados(): array {
    return $this->cursosAsignados();
}

public function cursosDelProfesor($profesor_id): array {
    $cursoDAL = new CursoDAL();
    return $cursoDAL->getCursosPorProfesor($profesor_id);
}




  
}

