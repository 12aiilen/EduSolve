<?php

class Cursos
{
    public int $id;
    private int $ano;
    private string $division;

    private int $idUsuario;

    public function __construct(int $id, int $ano, string $division, int $idUsuario)
    {
        $this->id = $id;
        $this->ano = $ano;
        $this->division = $division;
        $this->idUsuario = $idUsuario;
    }



    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of ano
     */
    public function getAno(): int
    {
        return $this->ano;
    }

    /**
     * Set the value of ano
     */
    public function setAno(int $ano): self
    {
        $this->ano = $ano;

        return $this;
    }

    /**
     * Get the value of division
     */
    public function getDivision(): string
    {
        return $this->division;
    }

    /**
     * Set the value of division
     */
    public function setDivision(string $division): self
    {
        $this->division = $division;

        return $this;
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario(): int
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     */
    public function setIdUsuario(int $idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }
}
