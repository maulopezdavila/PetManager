<?php

// Clase Mascota con sus atributos y métodos
class Mascota {

    // Atributos de la clase Mascota
    public $nombre;
    public $especie;
    public $raza;
    public $fechaNacimiento;
    public $color;

    // Arrays vacíos para guardar los dueños y las visitas
    public $duenos = [];
    public $visitas = [];

    // Constructor que se ejecutará cada vez que se cree una nueva mascota
    public function __construct($nombre, $especie) {
        $this->nombre = $nombre;
        $this->especie = $especie;
    }

    // Getters y setters para nombre
    public function getNombre() {
        return $this->nombre;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Getters y setters para especie
    public function getEspecie() {
        return $this->especie;
    }
    
    public function setEspecie($especie) {
        $this->especie = $especie;
    }

    // Getters y setters para raza
    public function getRaza() {
        return $this->raza;
    }
    
    public function setRaza($raza) {
        $this->raza = $raza;
    }

    // Getters y setters para fecha de nacimiento
    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }
    
    public function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    // Getters y setters para color
    public function getColor() {
        return $this->color;
    }
    
    public function setColor($color) {
        $this->color = $color;
    }

    // Métodos para manejar dueños
    public function getDuenos() {
        return $this->duenos;
    }
    
    public function agregarDueno($dueno) {
        $this->duenos[] = $dueno;
    }

    // Métodos para manejar visitas
    public function getVisitas() {
        return $this->visitas;
    }
    
    public function agregarVisita($visita) {
        $this->visitas[] = $visita;
    }

    // Método para convertir a array
    public function aArray() {
        return [
            'nombre' => $this->nombre,
            'especie' => $this->especie,
            'raza' => $this->raza,
            'fechaNacimiento' => $this->fechaNacimiento,
            'color' => $this->color,
            'duenos' => array_map(function($dueno) {
                return $dueno->aArray();
            }, $this->duenos),
            'visitas' => array_map(function($visita) {
                return $visita->aArray();
            }, $this->visitas)
        ];
    }
}
?>
