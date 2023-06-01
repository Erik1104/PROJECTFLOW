<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Model\Proyecto;

class DashboardController {

    public static function index (Router $router) {

        session_start();
        
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->view('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto (Router $router) {
        
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $proyecto = new Proyecto($_POST);
            
            //validacion
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {

                //generar url unica
                $hash= md5(uniqid());
                $proyecto->url = $hash;

                //almacenar el creador del usuario
                $proyecto->propietarioId = $_SESSION['id'];


                //guardar Proyecto
                $proyecto->guardar();

                //REDIRECCIONAR
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->view('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto (Router $router) {

        session_start();
        isAuth();

        $token = $_GET['id'];

        if(!$token) header('Location: /dashboard');
        //revisar que la persona que hizo el proyecto es quien lo creo
        $proyecto = Proyecto::where('url', $token);

        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router->view('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil (Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if(empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    //mensaje de error
                    Usuario::setAlerta('error', 'Este correo ya esta en uso');
                    $alertas = Usuario::getAlertas();
                } else {
                    //Guardar el registro
                    $usuario->guardar();
                
                    Usuario::setAlerta('exito', 'Guardado correctamente');
                    $alertas = Usuario::getAlertas();
    
                    //asignar el nombre nuevo
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }
 
        $router->view('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password (Router $router) {
        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD']=== 'POST') {

            $usuario = Usuario::find($_SESSION['id']);

            //sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)) {
                $resultado = $usuario->comprobar_password();

                if($resultado) {

                    $usuario->password = $usuario->password_nuevo;

                    //Eliminar propiedades no necessarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //hashear nueva contraseña
                    $usuario->hashearPassword();

                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito', 'Contraseña actualizada correctamente');
                        $alertas = Usuario::getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'Contraseña incorrecta');
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        $router->view('dashboard/cambiar-password', [
            'titulo' => 'Cambiar contraseña',
            'alertas'=> $alertas
        ]);
    }
}