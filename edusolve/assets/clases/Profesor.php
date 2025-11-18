<?php
class Profesor {
    private $id;
    private $legajo;
    private $nombre;
    private $apellido;
    private $email;
    private $telefono;
    private $activo;
    private $createdAt;
    private $calificaciones = [];

    public function __construct($id = null, $legajo = null, $nombre = null, $apellido = null, $email = null, $telefono = null, $activo = true, $createdAt = null) {
        $this->id = $id;
        $this->legajo = $legajo;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->activo = $activo;
        $this->createdAt = $createdAt;
    }


    public function getId() { 
        return $this->id; 
    }
    
    public function getLegajo() { 
        return $this->legajo; 
    }
    
    public function getNombre() { 
        return $this->nombre; 
    }
    
    public function getApellido() { 
        return $this->apellido; 
    }
    
    public function getEmail() { 
        return $this->email; 
    }
    
    public function getTelefono() { 
        return $this->telefono; 
    }
    
    public function getActivo() { 
        return $this->activo; 
    }
    
    public function getCreatedAt() { 
        return $this->createdAt; 
    }
    
    public function getCalificaciones() { 
        return $this->calificaciones; 
    }

    public function setId($id) { 
        $this->id = $id; 
    }
    
    public function setLegajo($legajo) { 
        $this->legajo = $legajo; 
    }
    
    public function setNombre($nombre) { 
        $this->nombre = $nombre; 
    }
    
    public function setApellido($apellido) { 
        $this->apellido = $apellido; 
    }
    
    public function setEmail($email) { 
        $this->email = $email; 
    }
    
    public function setTelefono($telefono) { 
        $this->telefono = $telefono; 
    }
    
    public function setActivo($activo) { 
        $this->activo = $activo; 
    }
    
    public function setCreatedAt($createdAt) { 
        $this->createdAt = $createdAt; 
    }
    
    public function setCalificaciones($calificaciones) { 
        $this->calificaciones = $calificaciones; 
    }

    // ==================== MÉTODOS ADICIONALES ====================
    
    public function getNombreCompleto() {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function estaActivo() {
        return $this->activo === true || $this->activo === 1 || $this->activo === '1';
    }

    public function getFechaCreacionFormateada($formato = 'd/m/Y H:i:s') {
        if ($this->createdAt) {
            return date($formato, strtotime($this->createdAt));
        }
        return null;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'legajo' => $this->legajo,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'activo' => $this->activo,
            'created_at' => $this->createdAt,
            'fecha_creacion_formateada' => $this->getFechaCreacionFormateada(),
            'nombre_completo' => $this->getNombreCompleto(),
            'esta_activo' => $this->estaActivo()
        ];
    }

    // Método para validar los datos del profesor
    public function esValido() {
        if (empty($this->legajo) || empty($this->nombre) || empty($this->apellido)) {
            return false;
        }
        
        if (!empty($this->email) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        return true;
    }
}
?>