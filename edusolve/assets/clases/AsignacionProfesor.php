<?php

class AsignacionProfesor
{
    
    private int $id;
    private int $usuarios_id;
    private int $materia_id;
    private int $curso_id;
    private string $horario;

    
    public function __construct(
        int $id = 0,
        int $usuarios_id = 0,
        int $materia_id = 0,
        int $curso_id = 0,
        string $horario = ''
    ) {
        $this->id = $id;
        $this->usuarios_id = $usuarios_id;
        $this->materia_id = $materia_id;
        $this->curso_id = $curso_id;
        $this->horario = $horario;
    }

    // 🔹 GETTERS
    public function getId(): int
    {
        return $this->id;
    }

    public function getUsuariosId(): int
    {
        return $this->usuarios_id;
    }

    public function getMateriaId(): int
    {
        return $this->materia_id;
    }

    public function getCursoId(): int
    {
        return $this->curso_id;
    }

    public function getHorario(): string
    {
        return $this->horario;
    }

    // 🔸 SETTERS
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUsuariosId(int $usuarios_id): void
    {
        $this->usuarios_id = $usuarios_id;
    }

    public function setMateriaId(int $materia_id): void
    {
        $this->materia_id = $materia_id;
    }

    public function setCursoId(int $curso_id): void
    {
        $this->curso_id = $curso_id;
    }

    public function setHorario(string $horario): void
    {
        $this->horario = $horario;
    }


}
