<?php

namespace Controllers;


use MVC\Router;

class CategoriasController {

    public static function index(Router $router){
     
        if(!is_auth() || $_SESSION['roll'] !=1){
            header('Location:/login');
        }
        $router->render('categorias/index', [
            'titulo' => 'Categorías',
            'nombre'=>$_SESSION['nombre']
        
        ]);
    }

}