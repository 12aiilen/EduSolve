<?php
class Alumno {
    private int $id;
    private string $DNI;
    private string $Nombre;
    private string $Apellido;
    private string $Genero;
    private string $Nacionalidad;
    private string $FechaNacimiento;
    private string $Direccion;
    private int $idCursos;
    private int $idTiposUsuarios;
    private int $idTutores;
    private int $idTurno;
    private ?string $turnoNombre;

    public function __construct(
        int $id,
        string $DNI,
        string $Nombre,
        string $Apellido,
        string $Genero,
        string $Nacionalidad,
        string $FechaNacimiento,
        string $Direccion,
        int $idCursos,
        int $idTiposUsuarios,
        int $idTutores,
        int $idTurno,
        ?string $turnoNombre = null

    ) {
        $this->id = $id;
        $this->DNI = $DNI;
        $this->Nombre = $Nombre;
        $this->Apellido = $Apellido;
        $this->Genero = $Genero;
        $this->Nacionalidad = $Nacionalidad;
        $this->FechaNacimiento = $FechaNacimiento;
        $this->Direccion = $Direccion;
        $this->idCursos = $idCursos;
        $this->idTiposUsuarios = $idTiposUsuarios;
        $this->idTutores = $idTutores;
         $this->idTurno = $idTurno; 
        $this->turnoNombre = $turnoNombre;;
  
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getDNI(): string { return $this->DNI; }
    public function getNombre(): string { return $this->Nombre; }
    public function getApellido(): string { return $this->Apellido; }
    public function getGenero(): string { return $this->Genero; }
    public function getNacionalidad(): string { return $this->Nacionalidad; }
    public function getFechaNacimiento(): string { return $this->FechaNacimiento; }
    public function getDireccion(): string { return $this->Direccion; }
    public function getIdCursos(): int { return $this->idCursos; }
    public function getIdTiposUsuarios(): int { return $this->idTiposUsuarios; }
    public function getIdTutores(): int { return $this->idTutores; }

    public function setDNI(string $DNI): void { $this->DNI = $DNI; }
    public function setNombre(string $Nombre): void { $this->Nombre = $Nombre; }
    public function setApellido(string $Apellido): void { $this->Apellido = $Apellido; }
    public function setGenero(string $Genero): void { $this->Genero = $Genero; }
    public function setNacionalidad(string $Nacionalidad): void { $this->Nacionalidad = $Nacionalidad; }
    public function setFechaNacimiento(string $FechaNacimiento): void { $this->FechaNacimiento = $FechaNacimiento; }
    public function setDireccion(string $Direccion): void { $this->Direccion = $Direccion; }
    public function setIdCursos(int $idCursos): void { $this->idCursos = $idCursos; }
    public function setIdTiposUsuarios(int $idTiposUsuarios): void { $this->idTiposUsuarios = $idTiposUsuarios; }
    public function setIdTutores(int $idTutores): void { $this->idTutores = $idTutores; }

    public function getIdTurno(): int {
        return $this->idTurno;
    }
        public function getTurnoNombre(): ?string {
        return $this->turnoNombre;
    }
    public function setIdTurno(int $idTurno): void {
    $this->idTurno = $idTurno;
}




}
