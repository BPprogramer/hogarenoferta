<?php 

    namespace Controllers;

use MVC\Router;

class PagosController {

    public static function index(Router $router){
        
        if(!is_auth()){
            header('Location:/login');
        }
        
        $router->render('pagos/index', [
            'titulo' => 'Pagos',
            'nombre'=>$_SESSION['nombre']
        
        ]);
    }

}