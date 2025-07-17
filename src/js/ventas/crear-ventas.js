(function () {
    // window.location.href = '/inicio'
    const seccionCrearVentas = document.querySelector('#seccion-crear-ventas')
    if (seccionCrearVentas) {

        let ventaId;
        let clienteId = null;
        let productoObj = {
            id: '',
            nombre: '',
            cantidad: '',
            precio_compra: '',
            precio_venta: '', //este es el precio de evnta de esta venta  ya que el usuario puede rebajarle al producto al momento  de venderlo
            // precio: '',
            precio_original: '', //este es el percio de venta con el que se registro el producto
            valor_total: '',
            stock: ''
        }
        let valoresObj = {
            total_sin_descuento: '',
            total_pagar: '',
            descuento: '',
            costo: '',
            total_libre: ''


        }
        let idAnterior;
        let focusCantidad = true;
        let productosArray = [];
        let granTotal = 0;
        let listadoProductos;





        const selectClientes = document.querySelector('#selectClientes');
        const guardarVentaBtn = document.querySelector('#guardar-venta');


        const totalInput = document.querySelector('#total');
        // const totalLibreInput = document.querySelector('#total_libre'); eliminamos mercado libre
        const descuentoInput = document.querySelector('#descuento');
        const totalPagarInput = document.querySelector('#total_pagar');
        const medotodoPago = document.querySelector('#metodo_pago');
        const pagoContado = document.querySelector('#pago-contado');
        const pagoCuotas = document.querySelector('#pago-cuotas');

        /* pago de contado */
        const cantidadPagar = document.querySelector('#cantidad_pagar');
        const cantidadCambio = document.querySelector('#cantidad_cambio');

        /* pago a cuotas o credito */
        const abono = document.querySelector('#abono');
        const saldo = document.querySelector('#saldo');

        //cliente
        const nombreCliente = document.querySelector('#nombre_cliente');
        const cedulaCliente = document.querySelector('#cedula_cliente');
        const celularCliente = document.querySelector('#celular_cliente');
        const direccionCliente = document.querySelector('#direccion_cliente');
        const emailCliente = document.querySelector('#email_cliente');

        // agregar cliente
        const bntAgregarCliente = document.querySelector('#agregar-cliente');
        const btnQuitarCliente = document.querySelector('#quitar-cliente');
        const contenedorCliente = document.querySelector('#contenedorCliente');

        //codigo de la venta
        const codigoVenta = document.querySelector('#codigo-venta');

        const codigoProducto = document.querySelector('#codigo-producto');
        const selectProductos = document.querySelector('#selectProductos');

        leerDatosUrl();
        consultarProductos();

        async function consultarProductos() {


            try {
                const respuesta = await fetch(`${location.origin}/api/productos-ventas`);
                const resultado = await respuesta.json();
                listadoProductos = resultado;
                productoPorCodigo(listadoProductos);


                // llenarPrimerOption(selectCategorias);
                const opcionDisabled = document.createElement('OPTION');
                opcionDisabled.textContent = '--seleccione un producto--';
                opcionDisabled.value = "0";

                selectProductos.appendChild(opcionDisabled);
                resultado.forEach(producto => {

                    const opcion = document.createElement('OPTION');
                    opcion.value = producto.id;
                    opcion.textContent = producto.nombre;


                    selectProductos.appendChild(opcion)

                });

                $('#selectProductos').select2()
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
            } catch (error) {

            }
            $('#selectProductos').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })


        }

        function productoPorCodigo(listadoProductos) {

            codigoProducto.addEventListener('input', function (e) {
                e.preventDefault();

                const miString = e.target.value

                //const primerCaracter = miString.charAt(0);
                const primerCaracter = miString

                // Verificar si el primer carácter es un número
                //if (!isNaN(parseInt(primerCaracter))) {
                if (!(/[^0-9]/.test(primerCaracter))) {
                    const productoSeleccionado = listadoProductos.filter(producto => {


                        return producto.codigo.toLowerCase() == e.target.value.toLowerCase()
                    })


                    if (productoSeleccionado.length > 0) {

                        consultarInfoProducto(productoSeleccionado[0].id)
                        e.target.value = '';
                    }
                } else {

                    codigoProducto.addEventListener('keydown', (e) => {
                        if (e.keyCode == 13) {
                            const productoSeleccionado = listadoProductos.filter(producto => {


                                return producto.codigo.toLowerCase() == e.target.value.toLowerCase()
                            })


                            if (productoSeleccionado.length > 0) {

                                consultarInfoProducto(productoSeleccionado[0].id)
                                e.target.value = '';
                            }
                        }
                    })
                }



            })
        }



        /* aqui hacemos click ya en el boton guardar venta coo tal y validamos */
        guardarVentaBtn.addEventListener('click', function () {
            revisarVenta();
        })

        medotodoPago.addEventListener('change', function () {

            if (contenedorCliente.classList.contains('d-none')) {
                contenedorCliente.classList.remove('d-none');
            }
            if (pagoContado.classList.contains('d-none')) {
                pagoContado.classList.remove('d-none');
            }
            if (pagoCuotas.classList.contains('d-none')) {
                pagoCuotas.classList.remove('d-none');
            }



            if (medotodoPago.value != 1) {

                abono.value = '';
                saldo.value = valoresObj.total_pagar.toLocaleString('en');
                resetearCliente();
                nombreCliente.readOnly = true;
                cedulaCliente.readOnly = true;
                celularCliente.readOnly = true;
                direccionCliente.readOnly = true;
                emailCliente.readOnly = true;
                btnQuitarCliente.disabled = true;
                pagoContado.classList.add('d-none');

            } else {
                clienteId = null;
                nombreCliente.readOnly = false;
                cedulaCliente.readOnly = false;
                celularCliente.readOnly = false;
                direccionCliente.readOnly = false;
                emailCliente.readOnly = false;
                btnQuitarCliente.disabled = false;
                contenedorCliente.classList.add('d-none');
                pagoCuotas.classList.add('d-none');



            }
        })


        bntAgregarCliente.addEventListener('click', function () {

            if (contenedorCliente.classList.contains('d-none')) {

                contenedorCliente.classList.remove('d-none');
            }
        });

        btnQuitarCliente.addEventListener('click', function () {
            resetearCliente();
            contenedorCliente.classList.add('d-none');
        })

        cantidadPagar.addEventListener('input', calcularCambio);

        abono.addEventListener('input', calcularSaldo);

        totalPagarInput.addEventListener('input', calcularDescuento);

        $('#selectProductos').on('select2:select', function (e) {

            consultarInfoProducto(e.target.value)
        });
        $('#selectClientes').on('select2:select', function (e) {
            if (e.target.value != 0) {

                consultarInfoCliente(e.target.value);
            } else {
                resetearCliente();
            }
        });




        async function cargarCodigoVenta() {

            try {
                const respuesta = await fetch(`${location.origin}/api/codigo-venta`);
                const codigo_venta = await respuesta.json();

                codigoVenta.value = codigo_venta;

            } catch (error) {

            }
        }

        //leer el id de la url para saber si es una edicion 
        function leerDatosUrl() {
            const urlActual = new URL(window.location);
            const params = new URLSearchParams(urlActual.search);
            if (params.size == 1) {
                ventaId = atob(params.get('id'));
                consultarVenta();

                consultarCLientes();


            } else {
                cargarCodigoVenta()
                consultarCLientes();
            }

        }

        /* cundo visitamos una venta existente la consultas */
        async function consultarVenta() {
            const url = `${location.origin}/api/venta?id=${ventaId}`;
            try {
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();
                llenarInformacion(resultado);
            } catch (error) {

            }
        }

        /* esto es para cando estamos editando la venta */
        function llenarInformacion(resultado) {
    
            clienteId = resultado.cliente_id;

            const productosVenta = resultado.productos_venta
            const venta = resultado.venta;
            codigoVenta.value = venta.codigo;
            // console.log(productosVenta)
            // console.log(venta)
            let total_sin_descuento = 0;

            productosVenta.forEach(productoVenta => {
                productoObj = {
                    id: productoVenta.id,
                    nombre: productoVenta.nombre,
                    cantidad: productoVenta.cantidad,
                    precio_compra: productoVenta.precio_compra,
                    precio_venta: productoVenta.precio_factura,
                    // precio: productoVenta.precio,
                    precio_original: productoVenta.precio_venta,
                    valor_total: productoVenta.precio_factura * productoVenta.cantidad,
                    stock: parseFloat(productoVenta.stock) + parseFloat(productoVenta.cantidad)

                }
                total_sin_descuento = parseFloat(total_sin_descuento) + parseFloat(productoVenta.precio_venta) * productoVenta.cantidad
                productosArray.push(productoObj)
                // total_sin_descuento = total_sin_descuento+productoVenta.precio_venta;
            })

            // valoresObj={
            //     total_sin_descuento,
            //     total_pagar:venta.recaudo,
            //     descuento:venta.descuento,
            //     costo:venta.costo


            // }
            mostrarProductos();

            totalInput.value = total_sin_descuento.toLocaleString('en');
            descuentoInput.value = resultado.venta.descuento + "%";
            totalPagarInput.value = parseFloat(resultado.venta.total_factura).toLocaleString('en');

            if (venta.nombre_cliente != '' || venta.cedula_cliente != '' || venta.celular_cliente != '' || venta.direccion_cliente != '' || venta.nombre != undefined) {
                contenedorCliente.classList.remove('d-none');
                nombreCliente.value = venta.nombre_cliente;
                cedulaCliente.value = venta.cedula_cliente;
                celularCliente.value = venta.celular_cliente;
                direccionCliente.value = venta.direccion_cliente;
                emailCliente.value = venta.email_cliente;
            }

            if (venta.metodo_pago == 2 || venta.metodo_pago == 3) {
                let optionToSelect = '';

                if (venta.metodo_pago == 2) {
                    optionToSelect = document.querySelector('#metodo_pago option[value="2"]');
                } else {
                    optionToSelect = document.querySelector('#metodo_pago option[value="3"]');
                }

                optionToSelect.selected = true;
          
                abono.value = (parseFloat(venta.recaudo)).toLocaleString('en');
                saldo.value = (venta.total_factura - venta.recaudo).toLocaleString('en');
                pagoContado.classList.add('d-none');
                pagoCuotas.classList.remove('d-none');


            }
        }

        function revisarVenta() {

            if (productosArray.length == 0) {
                Swal.fire({
                    icon: 'error',

                    text: 'No Hay productos agregados a esta venta',

                })
                return;
            }
         
            if (medotodoPago.value == 2) {
                if (selectClientes.value == 0) {
                    Swal.fire({
                        icon: 'error',

                        text: 'Para las ventas a credito o de mercado libre es necesario seleccionar un cliente que se encuentre registrado',

                    })
                    return;
                }


            }


            enviarInformacion();

        }
        async function enviarInformacion() {

      
            console.log(valoresObj)

            const datos = new FormData();
            if (ventaId) {
                datos.append('id', ventaId);
            }
            datos.append('productosArray', JSON.stringify(productosArray));
            datos.append('total_factura', valoresObj.total_pagar); //total de esta venta
            datos.append('total', valoresObj.total_sin_descuento); //total de sin descuento

            datos.append('costo', valoresObj.costo);
            datos.append('descuento', valoresObj.descuento);
            datos.append('metodo_pago', medotodoPago.value);
            if (medotodoPago.value != 1) {
                valor_abono = 0;
                if (abono.value != '') {
                    valor_abono = abono.value;
                }
                datos.append('abono', valor_abono);
                datos.append('saldo', saldo.value);
                datos.append('cliente_id', selectClientes.value)
                datos.append('recaudo', valor_abono);
                if (parseFloat(valor_abono) < parseFloat(valoresObj.total_pagar)) {
                    datos.append('pagado', 0);
                } else {
                    datos.append('pagado', 1);
                }

            } else {
                datos.append('pagado', 1);
                datos.append('recaudo', valoresObj.total_pagar);
            }


            datos.append('nombre_cliente', nombreCliente.value)
            datos.append('cedula_cliente', cedulaCliente.value)
            datos.append('celular_cliente', celularCliente.value)
            datos.append('direccion_cliente', direccionCliente.value);
            datos.append('email_cliente', emailCliente.value);
          

            let url;
            if (ventaId) {
                url = `${location.origin}/api/editar-venta`;
            } else {
                url = `${location.origin}/api/crear-venta`;
            }

            // guardarVentaBtn.disabled = true;
            try {

                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                });
              
                const resultado = await respuesta.json();
                console.log(resultado)
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




                    resetearVentaAnterior();
                }
                guardarVentaBtn.disabled = false;
                setTimeout(() => {
                    eliminarToastAnterior();
                }, 8000)
            } catch (error) {
                console.log(error)
            }

        }

        function resetearVentaAnterior() {
            ventaId = null;
            productosArray = [];
            productoObj = {
                id: '',
                nombre: '',
                cantidad: '',
                precio_compra: '',
                precio_venta: '',

                precio_original: '',
                valor_total: '',
                stock: ''
            }
            valoresObj = {
                total_sin_descuento: '',
                total_pagar: '',
                descuento: '',
                costo: ''


            }

            const selectProductos = $('#selectProductos');

            const selectClientes = $('#selectClientes');

            selectProductos.val("0").trigger('change.select2');

            selectClientes.val("0").trigger('change.select2');



            medotodoPago.innerHTML = `
                <option value="1">Pago e Contado</option>
                <option value="2">Pago a Cuotas</option>
                <option value="3">Mercado Libre</option>
            `

            cantidadPagar.value = '';
            cantidadCambio.value = '';
            abono.value = '';
            saldo.value = '';
            if (!contenedorCliente.classList.contains('d-none')) {
                contenedorCliente.classList.add('d-none');
            }
            const pagoContado = document.querySelector('#pago-contado');
            const pagoCuotas = document.querySelector('#pago-cuotas');
            if (!pagoCuotas.classList.contains('d-none')) {
                pagoCuotas.classList.add('d-none');
            }
            if (pagoContado.classList.contains('d-none')) {

                pagoContado.classList.remove('d-none');
            }


            cargarCodigoVenta();
            resetearCliente();
            mostrarProductos();

        }




        async function consultarInfoCliente(id) {


            try {
                const respuesta = await fetch(`${location.origin}/api/cliente?id=${id}`);
                const resultado = await respuesta.json();



                eliminarToastAnterior();

                if (resultado.type == 'error') {
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',

                        body: resultado.msg
                    })
                } else {



                    setTimeout(() => {
                        eliminarToastAnterior();
                    }, 4000)


                    imprimirDatosCliente(resultado);
                }

            } catch (error) {

            }


        }

        function imprimirDatosCliente(cliente) {

            nombreCliente.value = cliente.nombre ?? '';
            cedulaCliente.value = cliente.cedula ?? '';
            celularCliente.value = cliente.celular ?? '';
            direccionCliente.value = cliente.direccion ?? '';
            emailCliente.value = cliente.email ?? '';
        }


        function resetearCliente() {
            var selectClientes = $('#selectClientes');
            selectClientes.val("0").trigger('change.select2');
            nombreCliente.value = "";
            cedulaCliente.value = "";
            celularCliente.value = "";
            direccionCliente.value = "";
            emailCliente.value = "";
        }








        function calcularCambio(e) {
            cantidadPagar.value = formatearValor(e.target.value);
            const valor_pagar = parseFloat((cantidadPagar.value).replace(/,/g, ''));
            const cambio = valor_pagar - valoresObj.total_pagar;
            cantidadCambio.value = cambio.toLocaleString('en')

        }
        function calcularSaldo(e) {

            abono.value = formatearValor(e.target.value);
            const valor_abono = parseFloat((abono.value).replace(/,/g, ''));
            const deuda = valoresObj.total_pagar - valor_abono;
            saldo.value = deuda.toLocaleString('en')

        }




        function mostrarProductos() {
            const contenedorProductos = document.querySelector('#productosVenta');
            limpiarHtml(contenedorProductos);
            productosArray.forEach(producto => {
                const { id, nombre, precio_venta, cantidad, valor_total, stock } = producto;
          

                const rowDiv = document.createElement('DIV');
                rowDiv.classList.add('row', 'px-2');
                rowDiv.dataset.productoId = id;

                //Producto

                const col1Div = document.createElement('DIV');
                col1Div.classList.add('col-sm-4');

                const group1Div = document.createElement('DIV');
                group1Div.classList.add('input-group', 'mb-3');

                const prepend1Div = document.createElement('DIV');
                prepend1Div.classList.add('input-group-prepend');


                prepend1Div.innerHTML = `<span class="input-group-text bg-icono btn pointer"><i class="fa-solid fa-trash text-danger"></i></span>`;
                prepend1Div.onclick = () => {

                    eliminarProducto(id);
                }

                const inputNombre = document.createElement('INPUT');
                inputNombre.type = 'text';
                inputNombre.classList.add('form-control');
                inputNombre.readOnly = true;
                inputNombre.value = nombre;
                inputNombre.dataset.productoId = id;


                const col7Div = document.createElement('DIV');
                col7Div.classList.add('col-sm-2');

                const group7Div = document.createElement('DIV');
                group7Div.classList.add('input-group', 'mb-3');

                const prepend7Div = document.createElement('DIV');
                prepend7Div.classList.add('input-group-prepend');


                prepend7Div.innerHTML = `<span class="input-group-text bg-icono"> <i class="fas fa-hashtag text-azul"></i></i></span>`;


                const inputStock = document.createElement('INPUT');
                inputStock.type = 'text';
                inputStock.classList.add('form-control');
                inputStock.readOnly = true;
                inputStock.value = stock;
                inputStock.dataset.productoId = id;


                group1Div.appendChild(prepend1Div);
                group1Div.appendChild(inputNombre);
                col1Div.appendChild(group1Div);
                group7Div.appendChild(prepend7Div);
                group7Div.appendChild(inputStock);


                col7Div.appendChild(group7Div);

                //cantidad

                const col2Div = document.createElement('DIV');
                col2Div.classList.add('col-sm-2');

                const group2Div = document.createElement('DIV');
                group2Div.classList.add('input-group', 'mb-3');

                const prepend2Div = document.createElement('DIV');
                prepend2Div.classList.add('input-group-prepend');

                prepend2Div.dataset.productoId = id;
                prepend2Div.innerHTML = `<span class="input-group-text bg-icono"> <i class="fas fa-hashtag text-azul"></i></i></span>`;



                const inputCantidad = document.createElement('INPUT');
                inputCantidad.type = 'number';
                inputCantidad.classList.add('form-control');
                // inputCantidad.min = 1;

                inputCantidad.value = cantidad;


                if (idAnterior == id && focusCantidad == true) {

                    // inputPrecio.selectionStart = inputPrecio.value.length;
                    // inputPrecio.selectionEnd = inputPrecio.value.length;

                    // Enfoca el elemento input
                    setTimeout(function () {
                        inputCantidad.focus();
                    }, 0);

                }

                inputCantidad.oninput = function () {

                    if (parseFloat(inputCantidad.value) > parseFloat(stock)) {
                        Swal.fire({
                            icon: 'warning',
                            text: 'el producto está agotado',

                        })
                        modificarTotalPorProducto(stock, id, true);
                    } else {
                        modificarTotalPorProducto(inputCantidad.value, id, true); //modificamos el total al moficiar la cantidad
                    }


                };

                group2Div.appendChild(prepend2Div);
                group2Div.appendChild(inputCantidad);
                col2Div.appendChild(group2Div);

                //precio sin comision para cuando se usa mercado libre

                // const col5Div = document.createElement('DIV');
                // col5Div.classList.add('col-sm-2');
                // const group5Div = document.createElement('DIV');
                // group5Div.classList.add('input-group', 'mb-3');

                // const prepend5Div = document.createElement('DIV');
                // prepend5Div.classList.add('input-group-prepend');
                // prepend5Div.innerHTML = `<span class="input-group-text bg-icono"> <i class="fas fa-dollar-sign text-azul"></i></i></span>`;

                // const inputPrecioLibre = document.createElement('INPUT');
                // inputPrecioLibre.type = 'text';
                // inputPrecioLibre.classList.add('form-control');
                // inputPrecioLibre.value = parseFloat(precio).toLocaleString('en');


                // group5Div.appendChild(prepend5Div);
                // group5Div.appendChild(inputPrecioLibre);
                // col5Div.appendChild(group5Div);

                // inputPrecioLibre.oninput = () => {

                //     console.log(inputPrecioLibre.value)
                //     const nuevo_precio_venta = formatearValor(inputPrecioLibre.value);
                //     inputPrecioLibre.value = nuevo_precio_venta;
                //     producto.precio = parseFloat((nuevo_precio_venta).replace(/,/g, ''));

                //     total_libre = 0;
                //     productosArray.forEach(producto => {
                //         total_libre = total_libre + producto.precio * producto.cantidad;
                //     })
                //     valoresObj.total_libre = total_libre;
                //     totalLibreInput.value = total_libre.toLocaleString('en');
                // }



                //precio unitario para factura

                const col3Div = document.createElement('DIV');
                col3Div.classList.add('col-sm-2');

                const group3Div = document.createElement('DIV');
                group3Div.classList.add('input-group', 'mb-3');

                const prepend3Div = document.createElement('DIV');
                prepend3Div.classList.add('input-group-prepend');


                prepend3Div.innerHTML = `<span class="input-group-text bg-icono"> <i class="fas fa-dollar-sign text-azul"></i></i></span>`;

                const inputPrecio = document.createElement('INPUT');
                inputPrecio.type = 'text';
                inputPrecio.classList.add('form-control');
                inputPrecio.value = parseFloat(precio_venta).toLocaleString('en');


                if (idAnterior == id && focusCantidad == false) {

                    // inputPrecio.selectionStart = inputPrecio.value.length;
                    // inputPrecio.selectionEnd = inputPrecio.value.length;

                    // Enfoca el elemento input
                    setTimeout(function () {
                        inputPrecio.focus();
                    }, 0);

                }

                /* este sera el precio de venta como tal para la venta ya que el usuario puede editarlo al momento de venderlo */
                inputPrecio.oninput = () => {
                    console.log(inputPrecio.value)
                    const nuevo_precio_venta = formatearValor(inputPrecio.value);
                    inputPrecio.value = nuevo_precio_venta;
                    // inputPrecioLibre.value = nuevo_precio_venta
                    // producto.precio = parseFloat((nuevo_precio_venta).replace(/,/g, ''));
                    modificarTotalPorProducto(inputPrecio.value, id, false)
                }
                inputPrecio.focus()

                group3Div.appendChild(prepend3Div);
                group3Div.appendChild(inputPrecio);
                col3Div.appendChild(group3Div);

                //precio cantidad
                const col4Div = document.createElement('DIV');
                col4Div.classList.add('col-sm-2');

                const group4Div = document.createElement('DIV');
                group4Div.classList.add('input-group', 'mb-3');

                const prepend4Div = document.createElement('DIV');
                prepend4Div.classList.add('input-group-prepend');

                prepend4Div.dataset.productoId = id;
                prepend4Div.innerHTML = `<span class="input-group-text bg-icono"> <i class="fas fa-dollar-sign text-azul"></i></i></span>`;

                const inputPrecioCantidad = document.createElement('INPUT');
                inputPrecioCantidad.type = 'text';
                inputPrecioCantidad.readOnly = true;
                inputPrecioCantidad.classList.add('form-control');
                inputPrecioCantidad.value = parseFloat(valor_total).toLocaleString('en');


                group4Div.appendChild(prepend4Div);
                group4Div.appendChild(inputPrecioCantidad);
                col4Div.appendChild(group4Div);



                rowDiv.appendChild(col1Div);
                rowDiv.appendChild(col7Div);
                rowDiv.appendChild(col2Div);
                // rowDiv.appendChild(col5Div);
                rowDiv.appendChild(col3Div);
                rowDiv.appendChild(col4Div);


                contenedorProductos.appendChild(rowDiv)

                //resetar los inputs de metodo de pago
                resetarInputsMetodoPago();



            });

            calcularTotal();

        }

        function resetarInputsMetodoPago() {

            cantidadPagar.value = '';
            cantidadCambio.value = '';
            abono.value = '';
            saldo.value = valoresObj.total_pagar.toLocaleString('en');
        }
        function calcularTotal() {
    

            let total = 0; //valor a pagar con el precio de venta original
            let total_pagar = 0; //valor a pagar con modificaciones de precios 
            let total_costo = 0;
            // let total_libre = 0;
            productosArray.forEach(producto => {

                total = total + producto.cantidad * producto.precio_original;
                total_pagar = total_pagar + producto.cantidad * producto.precio_venta;
                total_costo = total_costo + producto.cantidad * producto.precio_compra;
                // total_libre = total_libre + producto.cantidad * producto.precio;
            })
          

            const descuento = 100 - total_pagar * 100 / total;
            descuentoInput.value = !isNaN(Number(descuento.toFixed(2))) ? Number(descuento.toFixed(2)) + '%' : 0 + '%';

            totalInput.value = total.toLocaleString('en');
             
            totalPagarInput.value = total_pagar.toLocaleString('en');
                        
            // totalLibreInput.value = total_libre.toLocaleString('en'); // lo elimina porque ya no existe
        

            //llenamos el objeto con la informacion del pago de la venta
            valoresObj.total_sin_descuento = total;
            valoresObj.total_pagar = total_pagar;
            valoresObj.descuento = !isNaN(Number(descuento.toFixed(2))) ? Number(descuento.toFixed(2)) : 0;
            valoresObj.costo = total_costo;
            // valoresObj.total_libre = total_libre;


        }

        function calcularDescuento(e) { //calculamos el descuento cuando se modifica el precio final
            if (productosArray.length == 0) {
                totalPagarInput.value = 0;
                return;
            }

            totalPagarInput.value = formatearValor(e.target.value);
            const total_pagar = parseFloat((totalPagarInput.value).replace(/,/g, ''));

            let total = 0;
            productosArray.forEach(producto => {
                total = total + producto.cantidad * producto.precio_original;

            })
            const descuento = 100 - total_pagar * 100 / total;

            descuentoInput.value = !isNaN(Number(descuento.toFixed(2))) ? Number(descuento.toFixed(2)) + '%' : 0 + '%';


            //lenamos el objeto que tien la infomracion del pago 
            valoresObj.total_pagar = total_pagar;
            valoresObj.descuento = descuento;

            resetarInputsMetodoPago();


        }


        //modificar valores al cambiar la cantidad
        function modificarTotalPorProducto(parametro, id, aux) {
            idAnterior = id;
            if (aux) {
                focusCantidad = true;
                productosArray = productosArray.map(producto => {
                    if (producto.id == id) {

                        if (parametro < 0) {

                            parametro = 0;
                        }
                        return {
                            ...producto,
                            cantidad: parametro,
                            valor_total: parametro * producto.precio_venta,

                        }

                    }
                    return producto;
                })

            } else {

                focusCantidad = false;
                let nuevo_precio_venta = parseFloat(parametro.replace(/,/g, ''));
                productosArray = productosArray.map(producto => {
                    if (producto.id == id) {
                        if (parametro == '') {

                            parametro = 0;
                        }

                        if (!isNaN(nuevo_precio_venta * producto.cantidad)) {

                            return {
                                ...producto,
                                precio_venta: nuevo_precio_venta,
                                valor_total: nuevo_precio_venta * producto.cantidad

                            }

                        } else {

                            return {
                                ...producto,
                                precio_venta: 0,
                                valor_total: 0

                            }
                        }


                    }
                    return producto;
                })

            }


            mostrarProductos();
        }


        //eliminar producto de la venta

        function eliminarProducto(idProducto) {

            productosArray = productosArray.filter(producto => {
                return producto.id != idProducto
            })

            mostrarProductos();
        }

        async function consultarInfoProducto(id) {

            try {
                const respuesta = await fetch(`${location.origin}/api/producto?id=${id}`);
                const resultado = await respuesta.json();



                if (resultado.stock > 0) {
                    const { id, nombre, precio_venta, stock, precio_compra } = resultado;
                    productoObj = {
                        id,
                        nombre,
                        cantidad: 1,
                        precio_compra,
                        precio_venta,
                        // precio: parseFloat(precio_venta),    //esto lo quitaos porque es paramercado libre
                        precio_original: parseFloat(precio_venta),
                        valor_total: precio_venta,
                        stock
                    }

                    const existe_producto = productosArray.some(producto => producto.id == id);

                    if (!existe_producto) {
                        productosArray.push(productoObj);
                        mostrarProductos();
                    } else {
                        Swal.fire({
                            icon: 'warning',

                            text: 'el producto ya ha sido agregado anteriormente',

                        })
                    }
                } else {
                    Swal.fire({
                        icon: 'error',

                        text: 'Producto no disponible en inventario',

                    })
                }

            } catch (error) {

            }

            // console.log(productosArray)
        }
        function formatearValor(valor) {

            let valor_sin_formato = parseFloat(valor.replace(/,/g, ''));
            if (isNaN(valor_sin_formato)) {
                valor_sin_formato = '';
            }
            const valor_formateado = valor_sin_formato.toLocaleString('en');
            return valor_formateado;
        }
        function limpiarHtml(referencia) {
            while (referencia.firstChild) {
                referencia.removeChild(referencia.firstChild)
            }
        }
        function eliminarToastAnterior() {
            if (document.querySelector('#toastsContainerTopRight')) {
                document.querySelector('#toastsContainerTopRight').remove()
            }
        }
        async function consultarCLientes() {
            limpiarHtml(selectClientes);

            try {
                const respuesta = await fetch(`${location.origin}/api/clientes-ventas`);
                const resultado = await respuesta.json();

                // llenarPrimerOption(selectCategorias);
                const opcionDisabled = document.createElement('OPTION');
                opcionDisabled.textContent = '--seleccione un cliente--';
                opcionDisabled.value = "0";





                resultado.forEach(cliente => {

                    const opcion = document.createElement('OPTION');
                    opcion.value = cliente.id;
                    opcion.textContent = cliente.nombre;

                    if (cliente.id == clienteId) {

                        opcion.selected = true;
                    }

                    selectClientes.appendChild(opcion)

                });

                $('#selectClientes').select2()
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
                consultarInfoCliente(clienteId);
            } catch (error) {

            }
            $('#selectProductos').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })


        }
    }
})();


