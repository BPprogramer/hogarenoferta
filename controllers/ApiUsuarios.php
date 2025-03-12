<?php 
    namespace Controllers;

use Model\Usuario;

    class ApiUsuarios{
        public static function usuarios(){
            $usuarios_todos = Usuario::all();
            $usuarios = array_filter($usuarios_todos, function($usuario){
                if($usuario->id!=1 ){
                    return $usuario;
                }
          
            });
            
            $data =[];
            foreach($usuarios as $key=>$usuario){
             

                    $acciones = "<div class='d-flex justify-content-center' >";
                    $acciones .="<button data-usuario-id ='".$usuario->id."' id='editar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Editar</span><i class='fas fa-user-pen'></i></button>";
                    $acciones .="<button data-usuario-id ='".$usuario->id."' id='eliminar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio' ><span class='toolMio-text'>Eliminar</span><i class='fas fa-trash' ></i></button>";
                  
                    $acciones .="</div>";
 
                  
 
                    $estado = '';
                     if($usuario->estado == 0){
                        $estado = "<div class='d-flex justify-content-center' >";
                        $estado .= "<button type='button' class='btn  w-75 btn-inline btn-secondary btn-sm ' style='min-width:70px'>Inactivo</button>";
                        $estado .= "</div >";
                     }else{
                        $estado = "<div class='d-flex justify-content-center'>";
                        $estado .= "<button type='button' class='btn w-75 btn-inline bg-azul text-white btn-sm' style='min-width:70px'>Activo</button>";
                        $estado .= "</div >";
                     }
                     

                    $roll = '';
                     if($usuario->roll == 0){
                        $roll = "Vendedor";
                     }else{
                       
                         $roll = "Administrador";
                     }

                     $data[]=[
                        $key + 1, 
                        $usuario->nombre,
                        $usuario->email,
                        $estado,
                        $roll,
                        $acciones
                     ];
                   
                     
                     
                 
                }    
             // Generar el JSON final
            $datoJson = json_encode(["data" => $data], JSON_UNESCAPED_SLASHES);

            echo $datoJson;
        }
        

        public static function consultarUsuario(){
            $id = $_GET['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta Nuevamente']);
                return;
            }

            $usuario = Usuario::find($id);
            echo json_encode($usuario);
            
        }

        public static function crear() {
        
            $existeEmail = Usuario::where('email', $_POST['email']);
            if($existeEmail){
                echo json_encode(['type'=>'error', 'msg'=>'El email ya está registrado, Intento con otro porfavor']);
                return;
            }
            $usuario = new Usuario($_POST);
            
            
            $usuario->hashPassword();
              
            $resultado =  $usuario->guardar();
    
            if($resultado){
                echo json_encode(['type'=>'success', 'msg'=>'el Usario Ha sido Registado Exitosamente']);
                return;
            }else{
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, intenta Nuevamente']);
                return;
            }  
    
        }
        public static function editar(){

            $id = $_POST['id'];
            if(!$id){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error Intenta Nuevamente']);
                return;
            }
            $email = $_POST['email'];
            if(!$email){
                echo json_encode(['type'=>'error', 'msg'=>'el Email no es válido']);
                return;
            }
    
            $usuario = Usuario::find($_POST['id']);
            if(!$usuario){
                echo json_encode(['type'=>'error', 'msg'=>'Hay un Problema con el usuario']);
                return;
            }
            if($usuario->email != $email){
                $existeEmail = Usuario::where('email',$email);
                if($existeEmail){
                    echo json_encode(['type'=>'error', 'msg'=>'El email ya está registrado, Intento con otro porfavor']);
                    return;
               
                }
            }
    
            $passwordActual = $usuario->password;
            $usuario->sincronizar($_POST);
            if($_POST['password']!=''){
                $usuario->password = $_POST['password'];
                $usuario->hashPassword();
            }else{
                $usuario->password = $passwordActual;
            }
    
            $resultado = $usuario->guardar();
            if($resultado){
                echo json_encode(['type'=>'success', 'msg'=>'El Usuario ha sido Actualizado con Exito']);
                return;
            }
            echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta nuevamente']);
            return;
       
        }
        
        public static function eliminar(){
            $id = $_POST['id'];
            if(!$id){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error Intenta Nuevamente']);
                return;
            }
            $usuario = Usuario::find($_POST['id']);
            if(!$usuario){
                echo json_encode(['type'=>'error', 'msg'=>'Hay un Problema con el usuario']);
                return;
            }
            $resultado = $usuario->eliminar();

            if($resultado['status']){
                echo json_encode(['type'=>'success', 'msg'=>'El Usuario ha sido Eliminado con Exito']);
                return;
            }
            echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta nuevamente']);
            return;
            
            
        }  

        public static function login(){
      
            if($_SERVER['REQUEST_METHOD'] === 'POST') {

                $email = filter_var($_POST['email'], FILTER_VALIDATE_INT);
                $email = $_POST['email'];
                if(!$email){
                    echo json_encode(['type'=>'error', 'msg'=>'el Email no es válido']);
                    return;
                }
             
                $usuario = Usuario::where('email', $email);
               
         
                if(!$usuario){
                    echo json_encode(['type'=>'error', 'msg'=>'el Email no está registrado']);
                    return;
                }

                if($usuario->estado == 0 ) {
                    echo json_encode(['type'=>'error', 'msg'=>'El Usuario no está activo, comuniquese con el encargado']);
                    return;
                }
                if( password_verify($_POST['password'], $usuario->password) ) {
                            
                    // Iniciar la sesión
                    if (session_status() != PHP_SESSION_ACTIVE) {
                        session_start();
                    } 
                      
                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre;
                 
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['roll'] = $usuario->roll;
                    echo json_encode(['type'=>'success', 'msg'=>'logueado']);
                    return;
                    
                } else {
                    echo json_encode(['type'=>'error', 'msg'=>'El password ingresado es incorrecto']);
                    return;
                }
                




            }

     
        }

        public static function logout(){
            session_start();
            $_SESSION = [];
            header('Location:/login');
        }
    }