<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas=[];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                // Comprobar que el usuario exista
                $usuario = Usuario::where('correo', $auth->correo);

                if($usuario) { 
                    // Verificar la contraseña y que este verificado
                    if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['correo'] = $usuario->correo;
                        $_SESSION['login'] = true;

                        // Redireccionamiento

                        if($usuario->admin === '1') {
                            // Admin
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        } else {
                            // Cliente
                            header('Location: /cita');
                        }
                    }
                    
                } else {
                    Usuario::setAlerta('error', 'usuario no encontrado');
                }

            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout() {

        session_start();

        $_SESSION = []; // Limpia la sessión

        header('Location: /');
    }

    public static function olvide(Router $router) {
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') { 
            $auth = new Usuario($_POST);
            $alertas = $auth->validarCorreo();

            if(empty($alertas)) {
                $usuario = Usuario::where('correo', $auth->correo);

                if($usuario && $usuario->confirmado === "1") {
                    // Generar token de un solo uso
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar el correo
                    $email = new Email($usuario->correo, $usuario->nombre . " " . $usuario->apellido, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito','Revisa tu correo');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {

        $alertas = [];
        $error = false;

        $token = s($_GET['token'] ?? '');

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario) || !$token) {
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }
 
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo Password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) { 
                // Borrar anterior contraseña aunque con sobrescribirla vale no hace falta esta linea
                $usuario->password = null;
                // Asignar nueva contraseña
                $usuario->password = $password->password;
                // hashear contraseña nueva
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {
        $usuario = new Usuario();

        // Arreglo alertas
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Actualizas el usuario con lo que llega por post
            $usuario->sincronizar($_POST);

            // Alertas
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alertas este vacio
            if(empty($alertas)) { 
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear contraseña
                    $usuario->hashPassword();

                    // Generar un token unico
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->correo, $usuario->nombre . " " . $usuario->apellido , $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear usuario
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
            
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token No Válido');
        } else {
            // Modificar a usuario confirmado
            
            $usuario->confirmado = '1';
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        // Obtener alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}