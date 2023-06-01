<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login (Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {

                //Verificar si un usuario existe
                $usuario = Usuario::where('email',$usuario->email);

                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    //Comprobar contraseña
                    if( password_verify( $_POST['password'], $usuario->password ) ) {

                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionar
                        header('Location: /dashboard');
                        
                    } else {
                        Usuario::setAlerta('error', 'Contraseña incorrecta.');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->view('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'alertas'=> $alertas
        ]);
    }

    public static function logout () {
        session_start();

        $_SESSION = [];

        header('Location: /');
        

    }

    public static function crear (Router $router) {

        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuentaNueva();

            if(empty($alertas)) {
             $existeUsuario = Usuario::where('email', $usuario->email);

               if($existeUsuario) {
                  Usuario::setAlerta('error', 'El usuario ya existe.');
                  $alertas = Usuario::getAlertas();
                } else {
                    //hashear password
                    $usuario->hashearPassword();

                    //eliminar password2
                    unset($usuario->password2);

                    //generar token
                    $usuario->generarToken();

                    //crear nuevo usuario
                    $resultado = $usuario->guardar();

                    //enviar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->view('auth/crear', [
            'titulo' => 'Crea tu Cuenta en ProjectFlow',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide (Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST); 
            $alertas = $usuario->validarEmail(); 

            if(empty($alertas)) {
                //Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado) {

                    //Generar nuevo token
                    $usuario->generarToken();
                    unset($usuario->password2);

                    //Actualizar el usuario
                    $usuario->guardar();

                    //enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    //Imprimir alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

                } else {
                    Usuario::setAlerta('error', 'El usuario no esta registrado o confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->view('auth/olvide', [
            'titulo' => 'Recupera tu contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function restablecer (Router $router) {

        $token = s($_GET['token']);
        $mostrar = true;

        if(!$token) header('Location: /');

        //Identificar el usuario con ese token
        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)) {

            Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            //Añadir nueva contraseña
            $usuario->sincronizar($_POST);

            //Validar contraseña
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                
                //hashear nueva contraseña
                $usuario->hashearPassword();

                //eliminar el token
                $usuario->token = null;

                //guardar el usuario en la BD
                $resultado = $usuario->guardar();

                if($resultado) {
                    header('Location: /');
                }
                
            }
        }

        $alertas = Usuario::getAlertas();

        $router->view('auth/restablecer', [
            'titulo' => 'Restablecer tu contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje (Router $router) {

        $router->view('auth/mensaje', [
            'titulo' => 'Mensaje de confirmacion'
        ]);
    }

    public static function confirmar (Router $router) {


        $token = s($_GET['token']);

   

        //encontrar al usuario con ese token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');

        } else {
            //confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);

            //guardar en la bd
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->view('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta',
            'alertas' => $alertas
        ]);
    }
}