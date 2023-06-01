<?php

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email','password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;



    public function __construct($args = []) {
     
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? null;
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    //VALIDACION LOGIN
    public function validarLogin () {

        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no valido';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alertas;
    }

    //Validacion para nuevas cuentas
    public function validarCuentaNueva () {

        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if(strlen($this->password) < 8 ) {
            self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }

        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Las contraseñas no coinciden';
        }

        return self::$alertas;
    }

    public function comprobar_password () : bool {
        return password_verify($this->password_actual, $this->password);
    }

    public function hashearPassword () : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken () {
        $this->token = uniqid();
    }

    public function validarEmail () {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no valido';
        }

        return self::$alertas;
    }

    public function validarPassword () {

        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
            
        }

        if(strlen($this->password) < 8) {
            self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }
        return self::$alertas;
    }

    public function validar_perfil () {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }

        return self::$alertas;
    }

    public function nuevo_password () : array {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'La contraseña actual no puede ir vacia';
        }

        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'La contraseña nueva no puede ir vacia';
        }

        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }
}    