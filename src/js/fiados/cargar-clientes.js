(function(){
    const seccionFiados = document.querySelector('#seccion-fiados');
    if(seccionFiados){

        const selectClientes = document.querySelector('#selectClientes');

        consultarCLientes();

        async function consultarCLientes(){
            
            
            try {
                const respuesta = await fetch(`${location.origin}/api/clientes-ventas-fiadas`);
                const resultado =  await respuesta.json();

       
               
                // llenarPrimerOption(selectCategorias);
                const opcionDisabled =   document.createElement('OPTION');
                opcionDisabled.textContent = '--seleccione un cliente--';
                opcionDisabled.value = "0";/*  */

                selectClientes.appendChild(opcionDisabled);
            
                resultado.forEach(cliente => {
                    
                    const opcion =   document.createElement('OPTION');
                    opcion.value = cliente.id;
                    opcion.textContent = cliente.nombre;
                   
                    selectClientes.appendChild(opcion)
            
                });
         
                $('#selectClientes').select2()
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
            } catch (error) {
                console.log(error)
            }
            $('#selectProductos').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        
       
        }  
    }
})();