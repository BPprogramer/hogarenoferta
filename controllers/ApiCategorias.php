<?php 
    namespace Controllers;


    use Model\Categoria;

    class ApiCategorias{
        public static function categorias(){
            $categorias = Categoria::all();
            
           
            $i=0;
                $data = [];
                 foreach($categorias as $key=>$categoria){
                    $i++;

                    $acciones = "<div class='d-flex justify-content-center' >";
                    $acciones .="<button data-categoria-id ='".$categoria->id."' id='editar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio' ><span class='toolMio-text'>Editar</span><i class='fas fa-pen'></i></button>";
                    $acciones .="<button data-categoria-id ='".$categoria->id."' id='eliminar'  type='button' class='btn btn-sm bg-hover-azul  mx-2  text-white toolMio'><span class='toolMio-text'>Eliminar</span><i class='fas fa-trash'></i></button>";
                    $acciones .="</div>";

                    $data[] = [
                        $key + 1,
                        $categoria->categoria,
                        $acciones
                    ];
                
                    
                 }
       
                 $datoJson = json_encode(["data" => $data], JSON_UNESCAPED_SLASHES);

                 echo $datoJson;
            
        }

        public static function categoriasAll(){
                $categorias = Categoria::all();
                echo json_encode($categorias);
        }

        public static function consultarCategoria(){
            $id = $_GET['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta Nuevamente']);
                return;
            }

            $categoria = Categoria::find($id);
            echo json_encode($categoria);
            
        }


        public static function crear(){
            
            $categoria = new Categoria($_POST);
            $resutlado = $categoria->guardar();
            if($resutlado){
                echo json_encode(['type'=>'success', 'msg'=>'La cateoría ha sido creada exitosamente']);
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
           
    
            $categoria = Categoria::find($_POST['id']);
            if(!$categoria){
                echo json_encode(['type'=>'error', 'msg'=>'Hay un Problema con la categoría']);
                return;
            }
        
            $categoria->sincronizar($_POST);
          
          
    
            $resultado = $categoria->guardar();
            if($resultado){
                echo json_encode(['type'=>'success', 'msg'=>'La Categoría ha sido Actualizado con Exito']);
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
            $categoria = Categoria::find($_POST['id']);
            if(!$categoria){
                echo json_encode(['type'=>'error', 'msg'=>'Hay un Problema con el Categoría']);
                return;
            }
            $resultado = $categoria->eliminar();
            if($resultado['status']){
                echo json_encode(['type'=>'success', 'msg'=>'La Categoría ha sido Eliminada con Exito']);
                return;
            }
            if($resultado['code']==1451){
                echo json_encode(['type'=>'error', 'msg'=>'No es Posible eliminar la categoría porque tiene productos  asociados']);
                return;
                
            }
         
            echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta nuevamente']);
            return;
            
            
        }  
    }