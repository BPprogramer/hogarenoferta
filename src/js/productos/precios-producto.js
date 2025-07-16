(function(){
    const productos = document.querySelector('#productos');
    if(productos){
        const precio_compra_input = document.querySelector('#precio_compra');
        const precio_venta_input = document.querySelector('#precio_venta');
        const porcentaje_venta = document.querySelector('#porcentaje_venta');
        const precio_paquete_input = document.querySelector('#precio_paquete');
        const unidades_input = document.querySelector('#unidades');
        precio_compra_input.addEventListener('input',function(e){
            const precio_compra = formatearValor(e.target.value);
            precio_compra_input.value = precio_compra;
            calcularPorcentaje()
        })
        precio_venta_input.addEventListener('input',function(e){
            const precio_compra = formatearValor(e.target.value);
            precio_venta_input.value = precio_compra;
            calcularPorcentaje();
            
        })
        porcentaje_venta.addEventListener('input',function(e){
           calcularPrecioVenta(e.target.value);
            
            
        })
        precio_paquete_input.addEventListener('input',function(e){
            const precio_paquete = formatearValor(e.target.value);
            precio_paquete_input.value = precio_paquete;
            calcularPrecioCompra();
        })
        unidades_input.addEventListener('input',function(e){
            
            calcularPrecioCompra();
        })

        function calcularPrecioCompra(){
            precio_compra_input.value = '';
            precio_venta_input.value = '';
            porcentaje_venta.value = '';
            const precio_paquete = parseFloat((precio_paquete_input.value).replace(/,/g, ''));
            const unidades = parseFloat(unidades_input.value);
            if(isNaN(precio_paquete) || isNaN(unidades)){
                return;
            }
            const precio_compra = precio_paquete/unidades;
            precio_compra_input.value = (Math.round(precio_compra)).toLocaleString('en')
            // calcularPrecioVenta(porcentaje_venta.input);
            // calcularPorcentaje();
            
        }


        function calcularPrecioVenta(porcentaje){
            const precio_compra = parseFloat((precio_compra_input.value).replace(/,/g, ''));
 
            if(isNaN(precio_compra)){
                return;
            }
            const precio_venta = parseFloat(porcentaje)*precio_compra/100;
            precio_venta_input.value = (Math.round(precio_venta / 100) * 100).toLocaleString('en')
        }
        
        function calcularPorcentaje(){
            
            const precio_compra = parseFloat((precio_compra_input.value).replace(/,/g, ''));
            const precio_venta = parseFloat((precio_venta_input.value).replace(/,/g, ''));
            if(isNaN(precio_compra) || isNaN(precio_venta)){
                return;
            }
            const porcentaje = ((precio_venta - precio_compra)*100)/precio_compra;
            porcentaje_venta.value = porcentaje.toFixed(2)
        }

        function formatearValor(valor){
           
            let valor_sin_formato = parseFloat(valor.replace(/,/g, ''));
            if(isNaN(valor_sin_formato)){
                valor_sin_formato = '';
            }
            const valor_formateado =  valor_sin_formato.toLocaleString('en');
            return valor_formateado ;
        }
       
    }
})();