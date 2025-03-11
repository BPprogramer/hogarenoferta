<?php

namespace Controllers;


use MVC\Router;

class UsuariosController {

    public static function index(Router $router){
        session_start();
        if(!is_auth() || $_SESSION['roll']!=1){
            header('Location:/login');
        }
        $router->render('auth/index', [
            'titulo' => 'Usuarios',
            'nombre'=>$_SESSION['nombre']
        
        ]);
    }


    public static function login(Router $router) {
        if(is_auth()){
            header('Location:/inicio');
        }

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión'
        ]);
    }
    public static function logout(){
        if(!($_SESSION)){
            session_start();
        }
        $_SESSION = [];
        header('Location:/login');
    }
    public static function redireccionLogin(){
        header('Location:/login');
    }
    

}