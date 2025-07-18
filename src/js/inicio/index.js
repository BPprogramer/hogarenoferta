(function () {
    const inicio = document.querySelector('#inicio');
    if (inicio) {

        const total_ventas = document.querySelector('#inicio_total_ventas')
        const total_recuados = document.querySelector('#inicio_total_recaudos')
        const costos = document.querySelector('#inicio_costos')
        const ganancia_no_realizada = document.querySelector('#inicio_ganancia_no_realizada')
        const ganancia_realizada = document.querySelector('#inicio_ganancia_realizada')
        const inventario = document.querySelector('#inicio_inventario')
        const numero_ventas = document.querySelector('#inicio_numero_ventas')
        const numero_fiados = document.querySelector('#inicio_numero_fiados')
        const numero_pagos = document.querySelector('#inicio_numero_pagos')
        const numero_cajas = document.querySelector('#inicio_numero_cajas')
        const numero_productos = document.querySelector('#inicio_numero_productos')
        const numero_clientes = document.querySelector('#inicio_numero_clientes')



        cargarInformacion();

        function mostrarInfo(resultado) {
           


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

        async function cargarInformacion() {
            const url = `${location.origin}/api/inicio`;
            try {
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();
                mostrarInfo(resultado);
            } catch (error) {
                console.log(error)
            }
        }
    }
})();