<?php
// class Usuario {
//     private int $id;
//     private string $nombre;
//     private string $apellido;
//     private string $email;
//     private string $contrasena;
//     private int $idTiposUsuarios;

//     public function __construct(int $id, string $nombre, string $apellido, string $email, string $contrasena, int $idTiposUsuarios) {
//         $this->id = $id;
//         $this->nombre = $nombre;
//         $this->apellido = $apellido;
//         $this->email = $email;
//         $this->contrasena = $contrasena;
//         $this->idTiposUsuarios = $idTiposUsuarios;
//     }

//     public function getId(): int { return $this->id; }
//     public function getNombre(): string { return $this->nombre; }
//     public function getApellido(): string { return $this->apellido; }
//     public function getEmail(): string { return $this->email; }
//     public function getContrasena(): string { return $this->contrasena; }
//     public function getIdTiposUsuarios(): int { return $this->idTiposUsuarios; }
//     public function getIdUsuarios(): int {
//     return $this->id;
// }


// }

// MODIFICADO: CLASE USUARIO PARA EDUSOLVE - TIPOS TOLERANTES
class Usuario
{
    private int $idUsuarios;
    private string $DNI;
    private string $Email;
    private string $Contrasena;
    private string $Nombre;
    private string $Apellido;
    private int $idTiposUsuarios;

    public function __construct(
        int $idUsuarios,
        string $DNI,
        string $Email,
        string $Contrasena,
        string $Nombre,
        string $Apellido,
        $idTiposUsuarios // SIN TIPO ESTRICTO, lo convertimos dentro
    ) {
        $this->idUsuarios = $idUsuarios;
        $this->DNI = $DNI;
        $this->Email = $Email;
        $this->Contrasena = $Contrasena;
        $this->Nombre = $Nombre;
        $this->Apellido = $Apellido;
        $this->idTiposUsuarios = (int)$idTiposUsuarios; // CONVERTIR A ENTERO
    }

    // GETTERS
    public function getIdUsuarios(): int { return $this->idUsuarios; }
    public function getDni(): string { return $this->DNI; }
    public function getEmail(): string { return $this->Email; }
    public function getContrasena(): string { return $this->Contrasena; }
    public function getNombre(): string { return $this->Nombre; }
    public function getApellido(): string { return $this->Apellido; }
    public function getIdTiposUsuarios(): int { return $this->idTiposUsuarios; }

    // SETTERS
    public function setDni(string $DNI): void { $this->DNI = $DNI; }
    public function setEmail(string $Email): void { $this->Email = $Email; }
    public function setContrasena(string $Contrasena): void { $this->Contrasena = $Contrasena; }
    public function setNombre(string $Nombre): void { $this->Nombre = $Nombre; }
    public function setApellido(string $Apellido): void { $this->Apellido = $Apellido; }
    public function setIdTiposUsuarios(int $idTiposUsuarios): void { $this->idTiposUsuarios = $idTiposUsuarios; }
}
?>
