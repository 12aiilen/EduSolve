<?php
require_once(__DIR__."../../clases/Tutor.php");
require_once(__DIR__."../../dal/TutorDAL.php");

class TutorBLL
{

    public function GrabarTutor($tutor)
    {
        $tutorDAL= new TutorDAL();
        $id= $tutorDAL->InsertarTutor($tutor);
        return $id;
    }
    public function getTutorByIdAlumno($idAlumno): Tutor
    {
        $tutorDAL= new TutorDAL();
        $resultado= $tutorDAL->findTutorByIdAlumno($idAlumno);
        return $resultado;
    }

    public static function getAllTutor()
    {

        $tutorDAL= new TutorDAL();
        $resultado= $tutorDAL->findAllTutor();
        return $resultado;
    }

    public function UpdateTutor($tutor)
    {
        $tutorDAL= new TutorDAL();
        $resultado= $tutorDAL->UpdateTutor($tutor);
        return $resultado;
    }

    public static function findTutorByIdAlumno($idTutor):Tutor
    {
        $tutorDAL= new TutorDAL();
        $resultado= $tutorDAL->findTutorByIdAlumno($idTutor);
        return $resultado;
    }


}