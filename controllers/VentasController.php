<?php 

    namespace Controllers;

    use MVC\Router;

    class VentasController{
        public static function index(Router $router){
      
            if(!is_auth()){
                header('Location:/login');
            }
            
            $router->render('ventas/index',[
                'titulo'=>'Administrar Ventas',
                'nombre'=>$_SESSION['nombre']
            ]);
        }
        public static function reporte(Router $router){
      
            if(!is_auth() || $_SESSION['roll']!=1){
                header('Location:/login');
            }
            
            $router->render('ventas/reporte',[
                'titulo'=>'Reporte de Ventas',
                'nombre'=>$_SESSION['nombre']
            ]);
        }

        public static function crear(Router $router){
            if(!is_auth()){
                header('Location:/login');
            }
            
            $router->render('ventas/crear-venta',[
                'titulo'=>'Crear Venta',
                'nombre'=>$_SESSION['nombre']
            ]);
        }
    }