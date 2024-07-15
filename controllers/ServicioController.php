<?php

namespace Controllers;

use MVC\Router;
use Model\Servicio;

class ServicioController {
    public static function index(Router $router) {
        isAdmin();
        $servicios = Servicio::all();
        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router) {
        isAdmin();
        $alertas = [];
        $servicio = new Servicio();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();
            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router) {
        isAdmin();
        $alertas = [];  
        if(!is_numeric($_GET['id']) || !$servicio = Servicio::find($_GET['id'])) header('Location: /servicios');
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();
            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }
        
        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar() {
        isAdmin();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!is_numeric($_POST['id']) || !$servicio = Servicio::find($_POST['id'])) header('Location: /servicios');
            $servicio->eliminar();
            header('Location: /servicios'); 
        }

    }
}