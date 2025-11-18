<?php
class Admin {
    private $id;
    private $usuario_id;
    private $fecha_alta;

    public function __construct($id = 0, $usuario_id = 0, $fecha_alta = '') {
        $this->id = $id;
        $this->usuario_id = $usuario_id;  
        $this->fecha_alta = $fecha_alta;
    }

    // Getters
    public function getId() {
        return $this->id;
    }  

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getFechaAlta() {
        return $this->fecha_alta;
    }

    // Método imprimir
    public function imprimir() {
        return "ID: " . $this->id . 
               " | Usuario ID: " . $this->usuario_id . 
               " | Fecha Alta: " . $this->fecha_alta;
    }

    
    public function getInfoCompleta() {
        return "Administrador #" . $this->id . 
               " - Registrado el: " . $this->fecha_alta;
    }
}