(function () {
    const productos = document.querySelector('#productos');
    if (productos) {
        const btnRegistrarProducto = document.querySelector('#registrar');
        const formulario = document.querySelector('#productoForm');
        const selectCategorias = document.querySelector('#categoria_id');
        const selectProveedores = document.querySelector('#proveedor_id');
        let formularioValidador;
        let idCategoria = null;
        let idProveedor = null;
        let tablaProductos;
        /* inputs del formulario */
        let id;
        const nombre = document.querySelector('#nombre');
        const codigo = document.querySelector('#codigo');
        const categoria_id = document.querySelector('#categoria_id');
        const proveedor_id = document.querySelector('#proveedor_id');
        const stock = document.querySelector('#stock');
        const stock_minimo = document.querySelector('#stock_minimo');
        const precio_compra = document.querySelector('#precio_compra');
        const precio_venta = document.querySelector('#precio_venta');
        const porcentaje_venta = document.querySelector('#porcentaje_venta');

        const btnSubmit = document.querySelector('#btnSubmit');
        btnSubmit.addEventListener('click', function () {
            //agregar validacion para el input cateogria_id y proveedor_id
        })

        mostrarProductos();
        //convertir el nombre a mayusculas
        nombre.addEventListener('input', function (e) {
            nombre.value = (e.target.value).toUpperCase();

        })

        btnRegistrarProducto.addEventListener('click', function () {
            id = null;
            idCategoria = null;
            idProveedor = null;
            accionesModal();
        })

        $('#tabla').on('click', '#editar', function (e) {
            id = e.currentTarget.dataset.productoId;
            accionesModal();
        })

        $('#tabla').on('click', '#eliminar', function (e) {
            const idProducto = e.currentTarget.dataset.productoId;
            alertaEliminarProducto(idProducto, e);
        })

        function alertaEliminarProducto(id, e) {

            const nombre = e.currentTarget.parentElement.parentElement.parentElement.childNodes[2].textContent;
            console.log(nombre)

            Swal.fire({
                icon: 'warning',
                html: `<h2 class="">esta seguro de eliminar el producto <span class="font-weight-bold"> ${nombre} </span>?</h2><br><p>Esta acción no se puede deshacer</p>`,

                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: `Cancelar`,


            }).then(result => {
                if (result.isConfirmed) {
                    eliminarProducto(id)
                }
            })
        }
        async function eliminarProducto(id) {
            const datos = new FormData();
            datos.append('id', id);

            url = `${location.origin}/api/producto/eliminar`;
            try {
                const respuesta = await fetch(url, {
                    body: datos,
                    method: 'POST'
                })
                const resultado = await respuesta.json();

                eliminarToastAnterior();

                if (resultado.type == 'error') {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',

                        body: resultado.msg
                    })
                } else {

                    $(document).Toasts('create', {
                        class: 'bg-azul text-blanco',
                        title: 'Completado',

                        body: resultado.msg
                    })

                    setTimeout(() => {
                        eliminarToastAnterior();
                    }, 8000)


                    tablaProductos.ajax.reload();
                }
            } catch (error) {

            }
        }


        // function mostrarProductos(){

        //     $("#tabla").dataTable().fnDestroy(); //por si me da error de reinicializar

        //     tablaProductos = $('#tabla').DataTable({
        //         language: {
        //             "decimal": "",
        //             "emptyTable": "No hay información",
        //             "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        //             "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        //             "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        //             "infoPostFix": "",
        //             "thousands": ",",
        //             "lengthMenu": "Mostrar _MENU_ Entradas",
        //             "loadingRecords": "Cargando...",
        //             "processing": "Procesando...",
        //             "search": "Buscar:",
        //             "zeroRecords": "Sin resultados encontrados",
        //             "paginate": {
        //                 "first": "Primero",
        //                 "last": "Ultimo",
        //                 "next": "Siguiente",
        //                 "previous": "Anterior"
        //             }
        //         },
        //         ajax: '/api/productos',
        //         "deferRender":true,
        //         "retrieve":true,
        //         "proccesing":true,
        //         responsive:true

        //     });

        //     // $.ajax({
        //     //     url:'/api/productos',
        //     //     dataType:'json',
        //     //     success:function(req){
        //     //         console.log(req)
        //     //     },
        //     //     error:function(error){
        //     //         console.log(error.resposeText)
        //     //     }
        //     // })

        // }  

        function mostrarProductos() {
            if ($.fn.DataTable.isDataTable('#tabla')) {
                $('#tabla').DataTable().destroy();
            }

            $('#tabla').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/productos',
                    type: 'GET'
                },
                responsive: true,
                order: [[1, 'desc']],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron resultados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        }

        /* consultar Categorias */
        async function consultarCategorias() {
            const url = `/api/productos-categorias`;
            try {
                const respuesta = await fetch(url);
                const categorias = await respuesta.json();
                llenarSelectCategorias(categorias);
            } catch (error) {

            }
        }
        /* consultar Proveedores */
        async function consultarProveedores() {
            const url = `/api/productos-proveedores`;
            try {
                const respuesta = await fetch(url);
                const proveedores = await respuesta.json();

                llenarSelectProveedores(proveedores);
            } catch (error) {

            }
        }

        function llenarSelectCategorias(categorias) {
            limpiarHtml(selectCategorias);
            // llenarPrimerOption(selectCategorias);

            // const opcionDisabled =   document.createElement('OPTION');
            //     opcionDisabled.textContent = '--seleccione un producto--';
            //     opcionDisabled.disabled = true;
            //     opcionDisabled.selected = true;

            //     selectCategorias.appendChild(opcionDisabled); 

            categorias.forEach(categoria => {

                const opcion = document.createElement('OPTION');
                opcion.value = categoria.id;
                opcion.textContent = categoria.categoria;
                if (categoria.id == idCategoria) {

                    opcion.selected = true;
                }


                selectCategorias.appendChild(opcion)

            });
            // $('.selectCategoria').select2()
            // $('.selectProveedor').select2()
            // $('.select2bs4').select2({
            //     theme: 'bootstrap4'
            // })

        }
        function llenarSelectProveedores(proveedores) {
            limpiarHtml(selectProveedores);

            // const opcionDisabled =   document.createElement('OPTION');
            //     opcionDisabled.textContent = '--seleccione un producto--';
            //     opcionDisabled.disabled = true;
            //     opcionDisabled.selected = true;

            //     selectProveedores.appendChild(opcionDisabled); 

            //  llenarPrimerOption(selectProveedores);
            proveedores.forEach(proveedor => {

                const opcion = document.createElement('OPTION');
                opcion.value = proveedor.id;
                opcion.textContent = proveedor.nombre;
                if (proveedor.id == idProveedor) {

                    opcion.selected = true;
                }
                selectProveedores.appendChild(opcion)

            });
            $('.selectCategoria').select2()
            $('.selectProveedor').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })


        }

        async function enviarDatos() {

            const datos = new FormData();
            if (id) {
                datos.append('id', id)
            }
            datos.append('nombre', (nombre.value).trim());
            datos.append('codigo', (codigo.value).trim());
            datos.append('categoria_id', (categoria_id.value));
            datos.append('proveedor_id', (proveedor_id.value));
            datos.append('stock', (stock.value));
            datos.append('stock_minimo', (stock_minimo.value));
            datos.append('precio_compra', (precio_compra.value));
            datos.append('precio_venta', (precio_venta.value));
            datos.append('porcentaje_venta', (porcentaje_venta.value));

            btnSubmit.disabled = true;

            let url = '';
            if (id) {
                url = `${location.origin}/api/producto/editar`;
            } else {
                url = `${location.origin}/api/producto/crear`;
            }

            try {
                const respuesta = await fetch(url, {
                    body: datos,
                    method: 'POST'
                })
                const resultado = await respuesta.json();

                eliminarToastAnterior();
                btnSubmit.disabled = false;
                if (resultado.type == 'error') {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',

                        body: resultado.msg
                    })
                } else {
                    tablaProductos.ajax.reload()

                    $(document).Toasts('create', {
                        class: 'bg-azul text-blanco',
                        title: 'Completado',

                        body: resultado.msg
                    })

                    setTimeout(() => {
                        eliminarToastAnterior();
                    }, 4000)

                    formulario.reset();

                    $('#modal-producto').modal('hide');

                }

            } catch (error) {
            }
        }

        function eliminarToastAnterior() {
            if (document.querySelector('#toastsContainerTopRight')) {
                document.querySelector('#toastsContainerTopRight').remove()
            }
        }

        async function accionesModal() {
            formulario.reset();
            btnSubmit.disabled = false;
            $('#modal-producto').modal('show');
            $('#productoForm').validate().resetForm();
            // Elimina todas las reglas de validación
            $('#productoForm').validate().destroy();
            // Elimina las clases de validación de los elementos
            $('#productoForm').find('.is-invalid').removeClass('is-invalid');
            if (id) {
                consultarProducto();
            }

            await consultarCategorias();
            await consultarProveedores();

            inicializarValidador();


        }

        async function consultarProducto() {
            try {
                const respuesta = await fetch(`/api/producto?id=${id}`);
                const resultado = await respuesta.json();


                llenarFormulario(resultado);
            } catch (error) {
                console.log(error)
            }
        }

        function llenarFormulario(resultado) {
            idCategoria = resultado.categoria_id;
            idProveedor = resultado.proveedor_id;
            nombre.value = (resultado.nombre).toUpperCase();
            codigo.value = resultado.codigo;
            stock.value = resultado.stock;
            stock_minimo.value = resultado.stock_minimo;
            precio_compra.value = (parseFloat(resultado.precio_compra)).toLocaleString('en');
            precio_venta.value = (parseFloat(resultado.precio_venta)).toLocaleString('en');
            porcentaje_venta.value = resultado.porcentaje_venta;

        }
        function inicializarValidador() {
            $.validator.setDefaults({
                submitHandler: function () {
                    enviarDatos();


                }
            });


            $('#productoForm').validate({
                rules: {
                    nombre: {
                        required: true

                    },

                    stock: {
                        required: true
                    },
                    stock_minimo: {
                        required: true
                    },
                    precio_compra: {
                        required: true
                    },
                    precio_venta: {
                        required: true
                    },
                    porcentaje_venta: {
                        required: true
                    }
                },
                messages: {
                    nombre: {
                        required: "El nombre es obligatorio"
                    },
                    stock: {
                        required: 'El stock inicial es obligatorio'
                    },
                    stock_minimo: {
                        required: 'El Stock mínimo es obligatorio'
                    },
                    precio_compra: {
                        required: 'El precio de compra es obligatorio'
                    },
                    precio_venta: {
                        required: 'El precio de venta es obligatorio'
                    },
                    porcentaje_venta: {
                        required: 'El porcentaje de venta es obligatorio'
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
        $('#productoForm').on('valid', function (event) {
            inicializarValidador();
        });

        // function llenarPrimerOption(referencia){
        //     const optionDefault =  document.createElement('OPTION');
        //     optionDefault.textContent = 'Seleccione';


        //     optionDefault.value=0;
        //     referencia.appendChild(optionDefault)
        // }
        function limpiarHtml(referencia) {
            while (referencia.firstChild) {
                referencia.removeChild(referencia.firstChild);
            }
        }


    }
})();