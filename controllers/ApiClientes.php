<?php 
    namespace Controllers;


    use Model\Categoria;
use Model\Cliente;

    class ApiClientes{
        public static function clientes(){
            $clientes = Cliente::all();
           
            
           
            $i=0;
            $data = [];
        
                 foreach($clientes as $key=>$cliente){
                    $i++;

                    $acciones = "<div class='d-flex justify-content-center' >";
                    $acciones .="<button data-cliente-id ='".$cliente->id."' id='editar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio' ><span class='toolMio-text'>Editar</span><i class='fas fa-pen'></i></button>";
                    $acciones .="<button data-cliente-id ='".$cliente->id."' id='eliminar'  type='button' class='btn btn-sm bg-hover-azul  mx-2  text-white toolMio'><span class='toolMio-text'>Eliminar</span><i class='fas fa-trash'></i></button>";
                    $acciones .="</div>";
                   
                    $deuda = "<div class='d-flex justify-content-center' >";
                    $deuda .= "<button type='button' class='btn  w-75 btn-inline btn-secondary btn-sm ' style='min-width:70px'>Calcular</span><i class='fas fa-math'></i></button>";
                    $deuda .= "</div >";

                    $data[] = [
                        $key+1,
                       $cliente->nombre,
                        $cliente->cedula,
                        $cliente->celular,
                        $cliente->direccion,
                        $cliente->email,
                        $deuda,
                   
                        $acciones
                    ];
                
                    }

                $datoJson = json_encode(["data" => $data], JSON_UNESCAPED_SLASHES);

                echo $datoJson;
            
        }

        public static function consultarCliente(){
            $id = $_GET['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
               
            }

            $cliente = Cliente::find( $id);
            if(!$cliente){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta Nuevamente']);
                return;
            }
            echo json_encode($cliente);
            
        }


        public static function crear(){


            $cliente = new Cliente($_POST);
    
            $resultado = $cliente->guardar();
            if($resultado){
                echo json_encode(['type'=>'success', 'msg'=>'El cliente ha sido registrado exitosamente']);
                return;
            }
            echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
            return;
        }

        public static function editar(){

            $id = $_POST['id'];
            if(!$id){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error Intenta Nuevamente']);
                return;
            }
           
    
            $cliente = Cliente::find($_POST['id']);
            if(!$cliente){
                echo json_encode(['type'=>'error', 'msg'=>'Hay un Problema con El Cliente']);
                return;
            }
        
            $cliente->sincronizar($_POST);
          
          
    
            $resultado = $cliente->guardar();
            if($resultado){
                echo json_encode(['type'=>'success', 'msg'=>'El Cliente ha sido Actualizado con Exito']);
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
            $cliente = Cliente::find($_POST['id']);
            if(!$cliente){
                echo json_encode(['type'=>'error', 'msg'=>'Hay un Problema con el Cliente']);
                return;
            }
            $resultado = $cliente->eliminar();
            if($resultado['status']){
                echo json_encode(['type'=>'success', 'msg'=>'El Cliente ha sido Eliminado con Exito']);
                return;
            }
            echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta nuevamente']);
            return;
            
            
        }  
    }