    <?php

    class   InformeAsistencia
    {
        private int $idAlumno;
        private string $nombre;
        private string $apellido;
        private string $tipoClase;
        private float $valorAsistencia;
        private DateTime $fechaAsistencia;
        private float $faltasTotales;

        public function __construct(int $idAlumno, string $nombre, string $apellido, string $tipoClase, float $valorAsistencia, DateTime $fechaAsistencia, float $faltasTotales){$this->idAlumno = $idAlumno;$this->nombre = $nombre;$this->apellido = $apellido;$this->tipoClase = $tipoClase;$this->valorAsistencia = $valorAsistencia;$this->fechaAsistencia = $fechaAsistencia;$this->faltasTotales = $faltasTotales;}
	
        public function getIdAlumno(): int {return $this->idAlumno;}

	public function getNombre(): string {return $this->nombre;}

	public function getApellido(): string {return $this->apellido;}

	public function getTipoClase(): string {return $this->tipoClase;}

	public function getValorAsistencia(): float {return $this->valorAsistencia;}

	public function getFechaAsistencia(): DateTime {return $this->fechaAsistencia;}

	public function getFaltasTotales(): float {return $this->faltasTotales;}

	
    }
