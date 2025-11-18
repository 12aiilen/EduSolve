<?php

class Asistencia
{
    private int $idAsistencia;
    private string $fechaAsistencia;

    private int $idTipoClase;

    private int $valorAsistencia;

    public function __construct(int $idAsistencia, string $fechaAsistencia, int $idTipoClase, int $valorAsistencia)
    {
        $this->idAsistencia = $idAsistencia;
        $this->fechaAsistencia = $fechaAsistencia;
        $this->idTipoClase = $idTipoClase;
        $this->valorAsistencia = $valorAsistencia;
    }


    /**
     * Get the value of idAsistencia
     */
    public function getIdAsistencia(): int
    {
        return $this->idAsistencia;
    }

    /**
     * Set the value of idAsistencia
     */
    public function setIdAsistencia(int $idAsistencia): self
    {
        $this->idAsistencia = $idAsistencia;

        return $this;
    }

    /**
     * Get the value of fechaAsistencia
     */
    public function getFechaAsistencia(): string
    {
        return $this->fechaAsistencia;
    }

    /**
     * Set the value of fechaAsistencia
     */
    public function setFechaAsistencia(string $fechaAsistencia): self
    {
        $this->fechaAsistencia = $fechaAsistencia;

        return $this;
    }

    /**
     * Get the value of idTipoClase
     */
    public function getIdTipoClase(): int
    {
        return $this->idTipoClase;
    }

    /**
     * Set the value of idTipoClase
     */
    public function setIdTipoClase(int $idTipoClase): self
    {
        $this->idTipoClase = $idTipoClase;

        return $this;
    }

    /**
     * Get the value of valorAsistencia
     */
    public function getValorAsistencia(): int
    {
        return $this->valorAsistencia;
    }

    /**
     * Set the value of valorAsistencia
     */
    public function setValorAsistencia(int $valorAsistencia): self
    {
        $this->valorAsistencia = $valorAsistencia;

        return $this;
    }
}
