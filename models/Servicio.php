<?php

namespace Model;

class Servicio extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar() {
        if(!$this->nombre) {
            static::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if(!$this->precio) {
            static::$alertas['error'][] = 'El precio es obligatorio';
        }

        if($this->precio && !is_numeric($this->precio)) {
            static::$alertas['error'][] = 'El precio es obligatorio';
        }
        return static::$alertas;
    }
}