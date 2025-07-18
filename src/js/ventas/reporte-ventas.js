(function () {


    const reporte = document.querySelector('#reporte');
    if (reporte) {
        const total_ventas = document.querySelector('#reporte_total_ventas')
        const total_recuados = document.querySelector('#reporte_total_recaudos')
        const costos = document.querySelector('#reporte_costos')
        const ganancia_no_realizada = document.querySelector('#reporte_ganancia_no_realizada')
        const ganancia_realizada = document.querySelector('#reporte_ganancia_realizada')
        const inventario = document.querySelector('#reporte_inventario')
        const numero_ventas = document.querySelector('#reporte_numero_ventas')
        const numero_fiados = document.querySelector('#reporte_numero_fiados')
        const numero_pagos = document.querySelector('#reporte_numero_pagos')
        const numero_cajas = document.querySelector('#reporte_numero_cajas')
        const numero_productos = document.querySelector('#reporte_numero_productos')
        const numero_clientes = document.querySelector('#reporte_numero_clientes')
        const fecha = document.querySelector('#fecha');
        fecha.addEventListener('input', function (e) {

            cargarInformacion(e.target.value);
        })


        llenarIputFecha();

        function llenarIputFecha() {
            const fecha_actual_utc = new Date();

            // Ajustar al huso horario de Colombia (UTC-5)
            const fecha_actual_colombia = new Date(fecha_actual_utc.getTime() - (5 * 60 * 60 * 1000));

            // Formatear la fecha y asignarla al input
            const fecha_actual_formateada = fecha_actual_colombia.toISOString().slice(0, 10);
            fecha.value = fecha_actual_formateada;

            // Cargar información con la fecha ajustada
            cargarInformacion(fecha.value);
        }



        function mostrarInfo(resultado) {
            console.log('asdf')
            console.log(resultado)
            total_ventas.textContent = resultado.total_ventas
            total_recuados.textContent = resultado.total_recaudos
            costos.textContent = resultado.costos
            ganancia_no_realizada.textContent = resultado.ganancia_no_realizada
            ganancia_realizada.textContent = resultado.ganancia_realizada
            inventario.textContent = resultado.inventario
            numero_ventas.textContent = resultado.numero_ventas
            numero_fiados.textContent = resultado.numero_fiados
            numero_pagos.textContent = resultado.numero_pagos
            numero_cajas.textContent = resultado.numero_cajas
            numero_productos.textContent = resultado.numero_productos
            numero_clientes.textContent = resultado.numero_clientes

        }

        async function cargarInformacion(fecha) {

            const datos = new FormData();
            datos.append('fecha', fecha);
            const url = `${location.origin}/api/info-general`;

            try {
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                });
                const resultado = await respuesta.json();
                console.log(resultado)
                // console.log(resultado);
                mostrarInfo(resultado);
            } catch (error) {
                console.log(error)
            }
        }
    }
})();