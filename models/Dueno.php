<?php

// Clase Dueño con sus atributos y métodos
class Dueno {

    // Atributos de Dueño
    public $nombre;
    public $telefono;
    public $email;
    public $direccion;

    // Constructor que se ejecutará cada vez que se cree un nuevo dueño
    public function __construct($nombre, $telefono) {
        $this->nombre = $nombre;
        $this->telefono = $telefono;
    }

    // Getters y setters para nombre
    public function getNombre() {
        return $this->nombre;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Getters y setters para teléfono
    public function getTelefono() {
        return $this->telefono;
    }
    
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    // Getters y setters para email
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }

    // Getters y setters para dirección
    public function getDireccion() {
        return $this->direccion;
    }
    
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    // Método para convertir a array 
    public function aArray() {
        return [
            'nombre' => $this->nombre,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'direccion' => $this->direccion
        ];
    }
}
?>
