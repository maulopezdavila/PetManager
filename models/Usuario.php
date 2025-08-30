<?php

// Clase Usuario para manejar el registro y autenticación
class Usuario {
    
    // Atributos de la clase Usuario
    public $nombreUsuario;
    public $hashPassword;
    public $fechaRegistro;
    
    // Constructor
    public function __construct($nombreUsuario, $hashPassword) {
        $this->nombreUsuario = $nombreUsuario;
        $this->hashPassword = $hashPassword;
        $this->fechaRegistro = date('Y-m-d H:i:s');
    }
    
    // Getters y setters para nombre de usuario
    public function getNombreUsuario() {
        return $this->nombreUsuario;
    }
    
    public function setNombreUsuario($nombreUsuario) {
        $this->nombreUsuario = $nombreUsuario;
    }
    
    // Getters y setters para password hash
    public function getHashPassword() {
        return $this->hashPassword;
    }
    
    public function setHashPassword($hashPassword) {
        $this->hashPassword = $hashPassword;
    }
    
    // Getter para fecha de registro
    public function getFechaRegistro() {
        return $this->fechaRegistro;
    }
    
    // Método para verificar contraseña
    public function verificarPassword($password) {
        return password_verify($password, $this->hashPassword);
    }
    
    // Método para convertir a array
    public function aArray() {
        return [
            'nombreUsuario' => $this->nombreUsuario,
            'hashPassword' => $this->hashPassword,
            'fechaRegistro' => $this->fechaRegistro
        ];
    }
    
    // Método para crear desde array 
    public static function desdeArray($datos) {
        $usuario = new Usuario($datos['nombreUsuario'], $datos['hashPassword']);
        $usuario->fechaRegistro = $datos['fechaRegistro'];
        return $usuario;
    }
}

?>
