<?php

namespace Model;

class Usuario extends ActiveRecord {
    // base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'correo', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    // Atributos
    public $id;
    public $nombre;
    public $apellido;
    public $correo;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if(!$this->apellido) {
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }

        if(!$this->telefono) {
            self::$alertas['error'][] = 'El telefono es obligatorio';
        }

        if($this->telefono && strlen($this->telefono) != 9) {
            self::$alertas['error'][] = 'El teléfono introducido no es válido';
        }

        if(!$this->correo) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if($this->password && strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 carácteres';
        }

        return self::$alertas;
    }

    public function validarLogin() {

        if(!$this->correo) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        
        return self::$alertas;
    }

    public function validarCorreo() {

        if(!$this->correo) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }
        
        return self::$alertas;
    }

    public function validarPassword() {

        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if($this->password && strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 carácteres';
        } 
        
        return self::$alertas;
    }

    // Revisa si el usuario existe
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE correo = '" . $this->correo . "' LIMIT 1";

        $resultado = self::$db->query($query);
        
        if($resultado->num_rows) {
            self::$alertas['error'][] = 'Este correo ya esta registrado';
        }

        return $resultado;
    }

    // Hashear contraseña
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT); // toma la contraseña y el metodo de hash
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {

        $resultado = password_verify($password, $this->password); // primer parametro contraseña insertada y segundo parametro contraseña de la base de datos

        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Contraseña Incorrecta o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }
}