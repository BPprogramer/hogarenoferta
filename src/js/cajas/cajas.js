(function(){
    const cajas = document.querySelector('#cajas');
    if(cajas){
    
        const btnAbrirCaja = document.querySelector('#registrar');
        const formulario = document.querySelector('#cajaForm');
        const btnSubmit = document.querySelector('#btnSubmit')

        let tablaCajas;
    
        let id;
        const efectivoAperturaInput = document.querySelector('#efectivo-apertura');

        efectivoAperturaInput.addEventListener('input', function(e){
            efectivoAperturaInput.value = formatearValor(e.target.value);
        })

        mostrarCajas();
      

        btnAbrirCaja.addEventListener('click',function(){
            id = null;
       
            accionesModal();
        })

      $('#tabla').on('click', '#editar', function(e){
 
            id=e.currentTarget.dataset.cajaId;
       
            accionesModal();
        })

   
        $('#tabla').on('click', '#eliminar', function(e){
            const id = e.currentTarget.dataset.cajaId;
            alertaEliminarCaja(id,e);
        }) 
        $('#tabla').on('click', '#cerrar', function(e){
            const id = e.currentTarget.dataset.cajaId;
            alertaCerrarCaja(id,e);
        }) 

        function alertaEliminarCaja(id,e){
            
            const efectivo_apertura = e.currentTarget.parentElement.parentElement.parentElement.childNodes[2].textContent;
           
            
            Swal.fire({
                icon:'warning',
                html: `<h2 class="">esta seguro de eliminar esta caja la cual tiene una efectivo de apertura de  <span class="font-weight-bold"> ${efectivo_apertura} </span>?</h2><br><p>Esta acción no se puede deshacer</p>`,
          
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: `Cancelar`,
                

            }).then(result=>{
                if(result.isConfirmed){
                    eliminarCaja(id)
                }
            })
        }
        function alertaCerrarCaja(id,e){
  
            Swal.fire({
                icon:'warning',
                html: `<h2 class="">¿Está seguro de cerrar esta caja? </h2><br><p>Esta acción no se puede deshacer</p>`,
          
                showCancelButton: true,
                confirmButtonText: 'Cerrar',
                cancelButtonText: `Cancelar`,
                

            }).then(result=>{
                if(result.isConfirmed){
                    cerrarCaja(id)
                }
            })
        }
        async function cerrarCaja(id){
           
            const datos = new FormData();
            datos.append('id', id);
         
    
            url = `${location.origin}/api/caja/cerrar`;
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

                    tablaCajas.ajax.reload(); 
                }
                setTimeout(()=>{
                    eliminarToastAnterior();
                },8000)
            } catch (error) { 
                
            }
        }
        async function eliminarCaja(id){
            const datos = new FormData();
            datos.append('id', id);
         
    
            url = `${location.origin}/api/caja/eliminar`;
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

                 

                 
                    tablaCajas.ajax.reload(); 
                }
                setTimeout(()=>{
                    eliminarToastAnterior();
                },8000)
            } catch (error) { 
                
            }
        }
      

        function mostrarCajas(){
      
            $("#tabla").dataTable().fnDestroy(); //por si me da error de reinicializar
    
            tablaCajas = $('#tabla').DataTable({
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
                ajax: '/api/cajas',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true
            });
            
            // $.ajax({
            //     url:'/api/cajas',
            //     dataType:'json',
            //     success:function(req){
            //         console.log(req)
            //     },
            //     error:function(error){
            //         console.log(error.resposeText)
            //     }
            // })
       
        }  



        async function enviarDatos(){

            const datos = new FormData();
            if(id){
                datos.append('id', id)
            }
            datos.append('efectivo_apertura', efectivoAperturaInput.value);

        
           btnSubmit.disabled = true;

            let url = '';
            if(id){
                url = `${location.origin}/api/caja/editar`;
            }else{
                url = `${location.origin}/api/caja/crear`;
            }

            
            try {
                const respuesta = await fetch(url,{
                    body:datos,
                    method: 'POST'
                })
                const resultado = await respuesta.json();
                
          
                eliminarToastAnterior();
            
                btnSubmit.disabled = false;
                if(resultado.type=='error'){
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                     
                        body: resultado.msg
                      })
                      $('#modal-caja').modal('hide');
                }else{
                    tablaCajas.ajax.reload()

                    $(document).Toasts('create', {
                        class: 'bg-azul text-blanco',
                        title: 'Completado',
                        
                        body: resultado.msg
                    })

                  

                    formulario.reset();

                    $('#modal-caja').modal('hide');
         
                }
                setTimeout(()=>{
                    eliminarToastAnterior();
                },4000)
   
            } catch (error) {  
            }
        }

        function eliminarToastAnterior(){
            if(document.querySelector('#toastsContainerTopRight')){
                document.querySelector('#toastsContainerTopRight').remove()
            }
        }

        async function accionesModal(){
            formulario.reset();
            btnSubmit.disabled = false;
            $('#modal-caja').modal('show');
            $('#cajaForm').validate().resetForm();
            // Elimina todas las reglas de validación
            $('#cajaForm').validate().destroy();
            // Elimina las clases de validación de los elementos
            $('#cajaForm').find('.is-invalid').removeClass('is-invalid');
            if(id){
                consultarCaja();
               
            }
           
         
     
            inicializarValidador();
       
           
        }

        async function consultarCaja(){
          
            try {
                const respuesta = await fetch(`/api/caja?id=${id}`);
                const resultado = await respuesta.json();
            
           
                llenarFormulario(resultado);
            } catch (error) {
                console.log(error)
            }
        }

        function llenarFormulario(resultado){
  

            efectivoAperturaInput.value = (parseFloat(resultado)).toLocaleString('en');
          
        }
        function inicializarValidador() {

            $.validator.setDefaults({
              submitHandler: function () {
                        enviarDatos();
                    
              }
            });
      
           
             $('#cajaForm').validate({
              rules: {
                efectivo_apertura: {
                    required: true
                      
                }
              },
              messages: {
                efectivo_apertura: {
                    required: "El campo es obligatorio, si el efectivo inicial es cero porfavor digite 0"  
                }
           

              },
              errorElement: 'span',
              errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
              },
              highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
              },
              unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
              }
            });
          };
          $('#cajaForm').on('valid', function(event) {
            inicializarValidador();
        });

        // function llenarPrimerOption(referencia){
        //     const optionDefault =  document.createElement('OPTION');
        //     optionDefault.textContent = 'Seleccione';
     
         
        //     optionDefault.value=0;
        //     referencia.appendChild(optionDefault)
        // }
        function formatearValor(valor){
           
            let valor_sin_formato = parseFloat(valor.replace(/,/g, ''));
            if(isNaN(valor_sin_formato)){
                valor_sin_formato = '';
            }
            const valor_formateado =  valor_sin_formato.toLocaleString('en');
            return valor_formateado ;
        }
        function eliminarToastAnterior(){
            if(document.querySelector('#toastsContainerTopRight')){
                document.querySelector('#toastsContainerTopRight').remove()
            }
        }
        function limpiarHtml(referencia){
            while(referencia.firstChild){
                referencia.removeChild(referencia.firstChild);
            }
        }



    }
})();