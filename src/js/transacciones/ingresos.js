(function(){
    const ingresos = document.querySelector('#ingresos');
    if(ingresos){
    
        const btnRegistrarIngreso = document.querySelector('#registrar');
        const formulario = document.querySelector('#ingresoForm');
        const btnSubmit = document.querySelector('#btnSubmit')

        let tablaIngresos;
    
        let id;
        const ingreso = document.querySelector('#ingreso');
        const descripcion = document.querySelector('#descripcion');

        ingreso.addEventListener('input', function(e){
            ingreso.value = formatearValor(e.target.value);
        })

        mostrarIngresos();
      

        btnRegistrarIngreso.addEventListener('click',function(){
            id = null;
       
            accionesModal();
        })

      $('#tabla').on('click', '#editar', function(e){
 
            id=e.currentTarget.dataset.ingresoId;
         
       
            accionesModal();
        })

   
        $('#tabla').on('click', '#eliminar', function(e){
            const id = e.currentTarget.dataset.ingresoId;
            alertaEliminarIngreso(id,e);
        }) 
      

        function alertaEliminarIngreso(id,e){
            
            const ingreso = e.currentTarget.parentElement.parentElement.parentElement.childNodes[3].textContent;
           
            
            Swal.fire({
                icon:'warning',
                html: `<h2 class="">esta seguro de eliminar el ingreso por un valor de <span class="font-weight-bold"> ${ingreso} </span>?</h2><br><p>Esta acción no se puede deshacer</p>`,
          
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: `Cancelar`,
                

            }).then(result=>{
                if(result.isConfirmed){
                    eliminarIngreso(id)
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

                    tablaIngresos.ajax.reload(); 
                }
                setTimeout(()=>{
                    eliminarToastAnterior();
                },8000)
            } catch (error) { 
                
            }
        }
        async function eliminarIngreso(id){
            const datos = new FormData();
            datos.append('id', id);
         
    
            url = `${location.origin}/api/ingreso/eliminar`;
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

                 

                 
                    tablaIngresos.ajax.reload(); 
                }
                setTimeout(()=>{
                    eliminarToastAnterior();
                },8000)
            } catch (error) { 
                
            }
        }
      

        function mostrarIngresos(){
      
            $("#tabla").dataTable().fnDestroy(); //por si me da error de reinicializar
    
            tablaIngresos = $('#tabla').DataTable({
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
                ajax: '/api/ingresos',
                "deferRender":true,
                "retrieve":true,
                "proccesing":true,
                responsive:true
            });
            
            $.ajax({
                url:'/api/ingresos',
                dataType:'json',
                success:function(req){
                 
                },
                error:function(error){
                    
                }
            })
       
        }  



        async function enviarDatos(){

            const datos = new FormData();
            if(id){
                datos.append('id', id)
            }   
          

            datos.append('ingreso', ingreso.value);
            datos.append('descripcion' , descripcion.value)

        
            btnSubmit.disabled = true;

            let url = '';
            if(id){
                url = `${location.origin}/api/ingreso/editar`;
            }else{
                url = `${location.origin}/api/ingreso/crear`;
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
                      $('#modal-ingreso').modal('hide');
                }else{
                   tablaIngresos.ajax.reload()

                    $(document).Toasts('create', {
                        class: 'bg-azul text-blanco',
                        title: 'Completado',
                        
                        body: resultado.msg
                    })

                  

                    formulario.reset();

                    $('#modal-ingreso').modal('hide');
         
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
            $('#modal-ingreso').modal('show');
            $('#ingresoForm').validate().resetForm();
            // Elimina todas las reglas de validación
            $('#ingresoForm').validate().destroy();
            // Elimina las clases de validación de los elementos
            $('#ingresoForm').find('.is-invalid').removeClass('is-invalid');
            if(id){
             
                consultarIngreso();
               
            }
           
         
     
            inicializarValidador();
       
           
        }

        async function consultarIngreso(){
          
          
            try {
                const respuesta = await fetch(`/api/ingreso?id=${id}`);
                const resultado = await respuesta.json();
            
               
                llenarFormulario(resultado);
                
            } catch (error) {
                console.log(error)
            }
        }

        function llenarFormulario(resultado){
            

            ingreso.value = (parseFloat(resultado.ingreso)).toLocaleString('en');
            descripcion.value = resultado.descripcion
          
        }
        function inicializarValidador() {

            $.validator.setDefaults({
              submitHandler: function () {
                        enviarDatos();
                      
                    
              }
            });
      
           
             $('#ingresoForm').validate({
              rules: {
                ingreso: {
                    required: true
                      
                },
                descripcion:{
                    required: true
                }
              },
              messages: {
                ingreso: {
                    required: "El monto que ingresa en la caja es obligatorio"  
                },
                descripcion:{
                    required: "La nota o descripción es obligatoria"
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
          $('#ingresoForm').on('valid', function(event) {
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