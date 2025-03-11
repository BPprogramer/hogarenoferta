<?php

namespace Controllers;
use MVC\Router;

    class ProductosVentasController{
        public static function index(Router $router){
            session_start();
            if(!is_auth() || $_SESSION['roll']!=1){
                header('Location:/login');
            }
        
            $router->render('productos-ventas/index', [
                'titulo' => 'Productos Vendidos',
                'nombre'=>$_SESSION['nombre']
            
            ]);
        }
    }