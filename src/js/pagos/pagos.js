(function(){
    const pagos = document.querySelector('#pagos');
    if(pagos){
   


        let tablaProductos;
    

        mostrarProductos();

        function mostrarProductos(){
      
           $("#tabla").dataTable().fnDestroy(); //por si me da error de reinicializar
    
            tablaProductos = $('#tabla').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                ajax: '/api/pagos',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true
                
            });
      
            // $.ajax({
            //     url:'/api/pagos',
            //     dataType:'json',
            //     success:function(req){
            //         console.log('consulado req')
            //         console.log(req)
            //     },
            //     error:function(error){
            //          console.log('consulado erros')
            //         console.log(error)
            //     }
            // })
       
        }  

        /* consultar Categorias */
     
    
    

     


     

    
       


    }
})();