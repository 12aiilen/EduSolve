<?php
class Preceptor {
    private int $id;
    private string $nombre;
    private string $apellido;
    private string $email;
    private int $idTipoUsuario;

    public function __construct(int $id, string $nombre, string $apellido, string $email, int $idTipoUsuario) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->idTipoUsuario = $idTipoUsuario;
    }

    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getApellido(): string { return $this->apellido; }
    public function getEmail(): string { return $this->email; }
    public function getIdTipoUsuario(): int { return $this->idTipoUsuario; }
}

?>
