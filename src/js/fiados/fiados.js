(function () {
    const seccionFiados = document.querySelector('#seccion-fiados');


    if (seccionFiados) {

        let datosDeuda = {
            monto: 0,
            deuda: 0,
            saldo: 0,
            venta_id: ''
        }

        let ventasInpagas = [];
        let idCliente = '';
        const btnSumitPago = document.querySelector('#btnSubmitPago');

        const bodyFiados = document.querySelector('#body-fiados');

        const totalDeuda = document.querySelector('#total-deuda');
        const btnPagar = document.querySelector('#pagar');
        const formulario = document.querySelector('#pagoForm')

        const deudaActual = document.querySelector('#deuda-actual');
        const btnPagarTodo = document.querySelector('#pagar-todo');
        const monto = document.querySelector('#monto');
        const saldoRestante = document.querySelector('#saldo-restante');

        btnPagarTodo.addEventListener('click', function () {
            datosDeuda.monto = datosDeuda.deuda
            monto.value = parseFloat(datosDeuda.monto).toLocaleString('en');
            calcularSaldoRestante()
        })

        monto.addEventListener('input', function (e) {

            const valor = e.target.value;

            let deudaSinFormat = parseFloat(valor.replace(/,/g, ''));

            if (deudaSinFormat == '') {
                deudaSinFormat = 0;
            }
            datosDeuda.monto = deudaSinFormat;
            calcularSaldoRestante()
            const monto_ingresado = formatearValor(valor);


            monto.value = monto_ingresado;

        })


        $('#selectClientes').on('select2:select', function (e) {
            if (e.target.value != 0) {

                consultarInfoCliente(e.target.value);
            } else {
                //resetearCliente();
            }
        });
        // btnPagar.addEventListener('click', function () {
        //     id = null;
        //     accionesModal();
        // })


        /* funcion para validar que el monto a pagar no sea mayor que el monto de la deuda */
        function validarMontoYSaldo() {
            if (datosDeuda.saldo < 0) {

                return false;
            }
            return true
        }

        async function enviarDatos() {

            const esValido = validarMontoYSaldo()
            if (!esValido) {
                Swal.fire({
                    icon: 'warning',
                    text: 'El monto a pagar no puede superar el valor de la deuda',
                })
                return;
            }


            const datos = new FormData();
            datos.append('venta_id', datosDeuda.venta_id);
            datos.append('monto', datosDeuda.monto);
            btnSumitPago.disabled = true;
            const url = `${location.origin}/api/pagar`;
            try {
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                });
                const resultado = await respuesta.json();

                btnSumitPago.disabled = false;
                $('#modal-pago').modal('hide');
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
                    consultarInfoCliente(idCliente)
                }
                setTimeout(() => {
                    eliminarToastAnterior();
                }, 8000)
            } catch (error) {

            }

        }

        function calcularSaldoRestante() {
            datosDeuda.saldo = datosDeuda.deuda - datosDeuda.monto;
            if (isNaN(datosDeuda.saldo)) {
                datosDeuda.saldo = datosDeuda.deuda;
            }
            saldoRestante.textContent = '$' + parseFloat(datosDeuda.saldo).toLocaleString('en')
        }

        function accionesModal() {

            formulario.reset();

            btnSumitPago.disabled = false;
            console.log(datosDeuda)

            deudaActual.value = parseFloat(datosDeuda.deuda).toLocaleString('en')
            saldoRestante.textContent = "$" + parseFloat(datosDeuda.saldo).toLocaleString('en');
            inicializarValidador();



        }




        async function consultarInfoCliente(id) {
            idCliente = id;

            try {
                saldoRestante.textContent = '$0';
                const respuesta = await fetch(`${location.origin}/api/pagos-cuotas?id=${id}`);

                const resultado = await respuesta.json();



                limpiarHtml(bodyFiados);

                if (resultado.ventas_fiadas.length == 0) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'No hay fiados para este cliente',
                    })
                    totalDeuda.textContent = 0;
                    datosDeuda.deuda = 0;
                    datosDeuda.monto = 0;
                    datosDeuda.saldo = 0;


                } else {
                    const { ventas_fiadas } = resultado;
                    mostrarFiados(ventas_fiadas);

                }

            } catch (error) {

                console.log(error)
            }
        }




        function alertaEliminarPago(pago) {


            Swal.fire({
                icon: 'warning',
                html: `<h2 class="">esta seguro de eliminar el pago número ${pago.payment_number} por un valor de <span class="font-weight-bold"> ${(parseFloat(pago.payment_amount)).toLocaleString('en')} </span>?</h2><br><p>Esta acción no se puede deshacer</p>`,

                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: `Cancelar`,


            }).then(result => {
                if (result.isConfirmed) {
                    eliminarPago(pago)
                }
            })
        }

        async function eliminarPago(pago) {

            const datos = new FormData();
            datos.append('id', pago.id);
            const url = `${location.origin}/api/eliminar-pago`;
            try {
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
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
                    $('#modal-pagos').modal('hide');
                    consultarInfoCliente(idCliente)

                }

                setTimeout(() => {
                    eliminarToastAnterior();
                }, 8000)
            } catch (error) {

            }
        }


        function mostrarFiados(ventas_fiadas) {

            let total_deuda = 0;

            ventas_fiadas.forEach(venta_fiada => {

                const { codigo, total_factura, recaudo, pagado } = venta_fiada;

                if (pagado != 0) {
                    ventasInpagas.push();
                }

                const tr = document.createElement('TR');

                const tdCodigo = document.createElement('TD');
                tdCodigo.textContent = '#' + codigo;

                const tdTotal = document.createElement('TD');
                tdTotal.textContent = '$' + parseFloat(total_factura).toLocaleString('en');

                const tdAbono = document.createElement('TD');
                tdAbono.textContent = '$' + parseFloat(recaudo).toLocaleString('en');

                const tdDeuda = document.createElement('TD');
                tdDeuda.textContent = '$' + parseFloat(total_factura - recaudo).toLocaleString('en');

                const tdEstado = document.createElement('TD');

                const divEstado = document.createElement('DIV');
                divEstado.classList.add('d-flex', 'justify-content-left', 'text-center');

                const btnEstado = document.createElement('BUTTON');
                btnEstado.type = 'button'

                btnEstado.classList.add('btn', 'w-40', 'btn-inline', 'btn-sm');

                if (pagado == 0) {
                    total_deuda = total_deuda + parseFloat(total_factura) - parseFloat(recaudo);
                    btnEstado.textContent = 'Pendiente';
                    btnEstado.classList.add('btn-danger');
                } else {
                    btnEstado.textContent = 'Pagado';
                    btnEstado.classList.add('bg-azul', 'text-white');
                }



                divEstado.appendChild(btnEstado);
                tdEstado.appendChild(divEstado);

                const tdInfo = document.createElement('TD');
                const divInfo = document.createElement('DIV');
                divInfo.classList.add('d-flex', 'justify-content-start');

                const btnInfo = document.createElement('BUTTON');
                btnInfo.type = 'button'
                btnInfo.classList.add('btn', 'btn-sm', 'bg-hover-azul', 'text-white', 'toolMio', 'mr-2');
                btnInfo.innerHTML = '<span class="toolMio-text">Ver venta</span><i class="fas fa-search"></i>';
                const btnPagos = document.createElement('BUTTON');
                btnPagos.type = 'button'
                btnPagos.classList.add('btn', 'btn-sm', 'bg-hover-azul', 'text-white', 'toolMio', 'mr-2');
                btnPagos.innerHTML = '<span class="toolMio-text">Pagos</span><i class="fas fa-money-bill-wave"></i>  ';

                const btnPagar = document.createElement('BUTTON');
                btnPagar.type = 'button'
                btnPagar.classList.add('btn', 'btn-sm', 'bg-hover-azul', 'text-white', 'toolMio');
                btnPagar.innerHTML = '<span class="toolMio-text">Pagar</span><i class="fas fa-coins"></i>  ';

                btnInfo.onclick = () => {
                    consultarInfoVentaFiada(venta_fiada); //consultamos la ifnromacion de la venta normal
                }
                btnPagos.onclick = () => {
                    cosultarPagosVentaFianda(venta_fiada); //consultamos los pagos que se han hecho a esta venta
                }
                btnPagar.onclick = () => {
                    pagarVentaFiada(venta_fiada); //consultamos los pagos que se han hecho a esta venta
                }

                divInfo.appendChild(btnInfo);
                divInfo.appendChild(btnPagos);
                divInfo.appendChild(btnPagar);
                tdInfo.appendChild(divInfo);

                tr.appendChild(tdCodigo)
                tr.appendChild(tdTotal)
                tr.appendChild(tdAbono)
                tr.appendChild(tdDeuda)
                tr.appendChild(tdEstado)
                tr.appendChild(tdInfo);
                bodyFiados.appendChild(tr);
                datosDeuda.cliente_id = venta_fiada.cliente_id;
            });
            totalDeuda.textContent = parseFloat(total_deuda).toLocaleString('en')
            datosDeuda.deuda = total_deuda;
            datosDeuda.saldo = total_deuda;

        }



        function mostrarInfoFiado(fiado, productos) {


            const codigoVenta = document.querySelector('#codigo-venta');
            const clienteVenta = document.querySelector('#cliente-venta');
            const fechaVenta = document.querySelector('#fecha-venta');

            const totalVenta = document.querySelector('#total-venta');
            const recaudoVenta = document.querySelector('#recaudo-venta');
            const saldoVenta = document.querySelector('#saldo-venta');

            codigoVenta.textContent = fiado.codigo
            clienteVenta.textContent = fiado.nombre_cliente
            fechaVenta.textContent = fiado.fecha
            totalVenta.textContent = (parseFloat(fiado.total)).toLocaleString('en');
            recaudoVenta.textContent = (parseFloat(fiado.recaudo)).toLocaleString('en');
            saldoVenta.textContent = (parseFloat(fiado.total - fiado.recaudo)).toLocaleString('en');

            const bodyProductos = document.querySelector('#body-productos-fiados');
            limpiarHtml(bodyProductos);

            productos.forEach(producto => {
                const { nombre, cantidad, precio } = producto
                const tr = document.createElement('TR');
                const tdNombre = document.createElement('td');
                tdNombre.textContent = nombre;
                const tdCantidad = document.createElement('td')
                tdCantidad.textContent = cantidad;
                const tdPrecio = document.createElement('td');
                tdPrecio.textContent = (parseFloat(precio)).toLocaleString('en');
                const tdSubTotal = document.createElement('td');
                tdSubTotal.textContent = (parseFloat(precio * cantidad)).toLocaleString('en');


                tr.appendChild(tdNombre)
                tr.appendChild(tdCantidad)
                tr.appendChild(tdPrecio)
                tr.appendChild(tdSubTotal)

                bodyProductos.appendChild(tr);
            })

        }

        /* para mostrar los pagos relacionados a una venta */
        function mostrarPagos(venta_fiada, pagos) {
            const bodyPagos = document.querySelector('#body-pagos');
            limpiarHtml(bodyPagos);
            pagos.forEach(pago => {
                const { payment_number, date, payment_amount, responsible, remaining_balance } = pago;

                const tr = document.createElement('TR');

                const tdPaymentNumber = document.createElement('TD');
                tdPaymentNumber.textContent = '#' + payment_number;

                const tdDate = document.createElement('TD');
                tdDate.textContent = date;
                const tdResponsible = document.createElement('TD');
                tdResponsible.textContent = responsible.nombre;


                const tdPaymentAmount = document.createElement('TD');
                tdPaymentAmount.textContent = '$' + parseFloat(payment_amount).toLocaleString('en');

                const tdRemainingaBalance = document.createElement('TD');
                tdRemainingaBalance.textContent = '$' + parseFloat(remaining_balance).toLocaleString('en');



                const tdActions = document.createElement('TD');
                const divActions = document.createElement('DIV');
                divActions.classList.add('d-flex', 'ustify-content-start');


                const btnDelete = document.createElement('BUTTON');
                btnDelete.type = 'button'
                btnDelete.classList.add('btn', 'btn-sm', 'bg-hover-azul', 'text-white', 'toolMio');
                btnDelete.innerHTML = '<span class="toolMio-text">Eliminar</span><i class="fas fa-trash"></i>';

                btnDelete.onclick = function () {
                    //eliminarPago(pago.numero_pago); //vamos a revisar que la 
                    alertaEliminarPago(pago);
                }
                // divActions.appendChild(btnInfo);
                divActions.appendChild(btnDelete);
                tdActions.appendChild(divActions);

                tr.appendChild(tdPaymentNumber)
                tr.appendChild(tdDate)
                tr.appendChild(tdResponsible)
                tr.appendChild(tdPaymentAmount)
                tr.appendChild(tdRemainingaBalance)


                tr.appendChild(tdActions);
                bodyPagos.appendChild(tr);

            });

        }

        async function consultarInfoVentaFiada(venta_fiada) {

            $('#modal-info').modal('show');

            try {
                const respuesta = await fetch(`${location.origin}/api/productos-fiados?venta_id=${venta_fiada.id}`);
                const resultado = await respuesta.json();

                if (resultado.type == 'error') {
                    Swal.fire({
                        icon: 'error',
                        text: resultado.msg,
                    })
                } else {

                    mostrarInfoFiado(venta_fiada, resultado)
                }
            } catch (error) {

            }



        }
        async function cosultarPagosVentaFianda(venta_fiada) {


            try {

                const respuesta = await fetch(`${location.origin}/api/pagos-por-venta?venta_id=${venta_fiada.id}`);
                const resultado = await respuesta.json();


                if (resultado.type == 'error') {
                    Swal.fire({
                        icon: 'error',
                        text: resultado.msg,
                    })
                } else {

                    $('#modal-pagos').modal('show');
                    mostrarPagos(venta_fiada, resultado.data)
                }
            } catch (error) {

            }

        }
        async function pagarVentaFiada(venta_fiada) {

            const { total_factura, recaudo, id, pagado } = venta_fiada
            if (pagado == 1) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Esta venta ya se encuentra pagada en su totalidad',
                })
            } else {
                $('#modal-pago').modal('show');
                datosDeuda.deuda = total_factura - recaudo
                datosDeuda.saldo = total_factura - recaudo
                datosDeuda.venta_id = id

                accionesModal()
            }







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


        function inicializarValidador() {
            $.validator.setDefaults({
                submitHandler: function () {
                    enviarDatos();
                }
            });

            // Función para validar que el valor sea diferente de "0"
            function notEqualChar(value, element, param) {
                return value !== param;
            }

            $('#pagoForm').validate({
                rules: {
                    monto: {
                        required: true,
                        customValidation: '0' // Carácter que se debe evitar
                    }
                },
                messages: {
                    monto: {
                        required: 'El monto a pagar es obligatorio',
                        customValidation: 'El monto no puede ser igual a "0"'
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

            // Agregar la regla personalizada utilizando la función
            $.validator.addMethod('customValidation', function (value, element) {
                return notEqualChar(value, element, '0');
            }, 'Este campo no puede ser igual a "0"');
        }

        function eliminarToastAnterior() {
            if (document.querySelector('#toastsContainerTopRight')) {
                document.querySelector('#toastsContainerTopRight').remove()
            }
        }

        // // Llamar a la función de inicialización al cargar la página
        // $(document).ready(function () {
        //     inicializarValidador();
        // });

        // // Volver a inicializar el validador cuando se detecta que el formulario es válido
        // $('#pagoForm').on('valid', function (event) {
        //     inicializarValidador();
        // });

    }


})();