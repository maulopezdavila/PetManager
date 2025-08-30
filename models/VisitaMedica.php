<?php

// Clase VisitaMedica con sus atributos y métodos
class VisitaMedica {

    // Atributos de la clase 
    public $fecha;
    public $diagnostico;
    public $tratamiento;
    public $motivo;

    // Constructor que se ejecutará cada vez que se cree una nueva visita médica
    public function __construct($fecha, $diagnostico, $tratamiento) {
        $this->fecha = $fecha;
        $this->diagnostico = $diagnostico;
        $this->tratamiento = $tratamiento;
    }

    // Getters y setters para fecha
    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    // Getters y setters para diagnóstico
    public function getDiagnostico() {
        return $this->diagnostico;
    }

    public function setDiagnostico($diagnostico) {
        $this->diagnostico = $diagnostico;
    }

    // Getters y setters para tratamiento
    public function getTratamiento() {
        return $this->tratamiento;
    }

    public function setTratamiento($tratamiento) {
        $this->tratamiento = $tratamiento;
    }

    // Getters y setters para motivo
    public function getMotivo() {
        return $this->motivo;
    }

    public function setMotivo($motivo) {
        $this->motivo = $motivo;
    }

    // Método para convertir a array 
    public function aArray() {
        return [
            'fecha' => $this->fecha,
            'diagnostico' => $this->diagnostico,
            'tratamiento' => $this->tratamiento,
            'motivo' => $this->motivo
        ];
    }
}
?>
