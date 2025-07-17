(function(){
    const ventas = document.querySelector('#ventas');
    if(ventas){
        let tablaVentas;
    
        $('#tabla').on('click', '#editar', function(e){
       
          
            id=e.currentTarget.dataset.ventaId;

    
            revisarPagosAsociados(id);

      
            // accionesModal();
        })
        $('#tabla').on('click', '#eliminar', function(e){
            const ventaId = e.currentTarget.dataset.ventaId;
         
            alertaEliminarVenta(ventaId,e);
        })
        $('#tabla').on('click', '#imprimir', function(e){
            const ventaId = e.currentTarget.dataset.ventaId;
            imprimirVenta(ventaId);
        })

        async function imprimirVenta(id){
            const datos = new FormData();
            datos.append('id', id);
            const url = `${location.origin}/api/imprimir-venta?id=${id}`;
            window.open(url, "_blank");
         
            // try {
            //     const respuesta = await fetch(url)
            //     const resultado = await respuesta.json();
            //     console.log(resultado)
            // } catch (error) {
            //     console.log(error)
            // }

     
          
        }
    

        async function revisarPagosAsociados(id){
            
            const datos = new FormData();
      
            datos.append('id', id);
            const url = `${location.origin}/api/revisar-venta`;
            try {
                const respuesta = await fetch(url,{
                    method:'POST',
                    body:datos
                 })
     
                 const resultado = await respuesta.json();

                   
               
                 eliminarToastAnterior();
               
                 if(resultado.type=='error'){
                     $(document).Toasts('create', {
                         class: 'bg-danger',
                         title: 'Error',
                      
                         body: resultado.msg
                       })
                 }else{
              
                    const idString = id.toString();
                    window.location = `/crear-venta?id=${btoa(idString)}`;
                     
                 }

                 setTimeout(()=>{
                    eliminarToastAnterior();
                 },8000)
            } catch (error) {
                console.log(error)
            }
        }

        function alertaEliminarVenta(id, e){
  
            const numero_venta = e.currentTarget.parentElement.parentElement.parentElement.childNodes[1].textContent;
          
            
            Swal.fire({
                icon:'warning',
                html: `<h2 class="">esta seguro de eliminar la venta numero <span class="font-weight-bold"> ${numero_venta} </span>?</h2><br><p>Esta acción no se puede deshacer</p>`,
          
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: `Cancelar`,
                

            }).then(result=>{
                if(result.isConfirmed){
                    eliminarVenta(id)
                }
            })
        }

        async function eliminarVenta(id){
            const datos = new FormData();
            datos.append('id', id);
    
            url = `${location.origin}/api/venta/eliminar`;
            try {
                const respuesta = await fetch(url,{
                    body:datos,
                    method: 'POST'
                })
                const resultado = await respuesta.json();
            
         
                
                eliminarToastAnterior();
            
                if(resultado.type=='error'){
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                     
                        body: resultado.msg
                      })
                }else{

                    $(document).Toasts('create', {
                        class: 'bg-azul text-blanco',
                        title: 'Completado',
                        
                        body: resultado.msg
                    })

                    setTimeout(()=>{
                        eliminarToastAnterior();
                    },8000)

                 
                    tablaVentas.ajax.reload(); 
                }
            } catch (error) { 
                
            }
        }

        mostrarVentas()
        function mostrarVentas(){
      
            $("#tabla").dataTable().fnDestroy(); //por si me da error de reinicializar
    
            tablaVentas = $('#tabla').DataTable({
                ajax: '/api/ventas',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true,
               
            });
 
            
            // $.ajax({
            //     url:'/api/ventas',
            //     dataType:'json',
            //     success:function(req){
            //         console.log(req)
            //     },
            //     error:function(error){
            //         console.log(error.resposeText)
            //     }
            // })
       
        }  
        function eliminarToastAnterior(){
            if(document.querySelector('#toastsContainerTopRight')){
                document.querySelector('#toastsContainerTopRight').remove()
            }
        }
    }
})();