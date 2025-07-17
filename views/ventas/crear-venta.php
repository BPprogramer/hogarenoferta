<?php //include_once __DIR__ . '/../templates/content-header.php'; 
?>
<div class="container-fluid px-3 mt-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header px-2">
                    <div class="row  justify-content-between align-items-center">
                        <div class="col-4">
                            <h3 class="card-title text-xl">Crear Venta</h3>
                        </div>
                        <div class="col-4 d-flex justify-content-end">
                            <a href="/ventas" id="registrar" class="btn bg-hover-azul text-white toolMio">
                                Ver Ventas
                            </a>
                        </div>
                    </div>
                </div><!--  -->
            </div>
        </div>
    </div>


</div>
<div class="content" id="seccion-crear-ventas">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">

                <div class="card card-info card-outline">
                    <div class="row px-2">
                        <div class="col-sm-4 my-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn pl-0 font-weight-bold">Producto</button>
                                </div>
                                <select class="form-control selectCategoria select2bs4" id="selectProductos">


                                </select>
                            </div>


                        </div>
                        <div class="col-sm-2 my-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn font-weight-bold pl-0">Código</button>
                                </div>
                                <input type="text" value="" id="codigo-producto" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4 my-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn pl-0 font-weight-bold">Responsable</button>
                                </div>
                                <input type="text" value="<?php echo $nombre ?>" class="form-control" readonly>
                            </div>

                        </div>
                        <div class="col-sm-2 my-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn font-weight-bold pl-0">Venta</button>
                                </div>
                                <input type="text" value="" id="codigo-venta" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row px-2">
                        <div class="col-sm-6">
                            <p class="font-bold font-weight-bold">Producto</p>
                        </div>
                        <div class="col-sm-2">
                            <p class="font-bold font-weight-bold">Cantidad</p>
                        </div>
                   <!--      <div class="col-sm-2">
                            <p class="font-bold font-weight-bold">Precio Sin Comisión</p>
                        </div> -->
                        <div class="col-sm-2">
                            <p class="font-bold font-weight-bold">Precio Venta</p>
                        </div>
                        <div class="col-sm-2">
                            <p class="font-bold font-weight-bold">Sub Total</p>
                        </div>
                    </div>
                    <div id="productosVenta">

                    </div>
                </div>


                <!-- metodo de pago y total a pgar -->
                <div class="row">
                    <!-- metodos de pago -->
                    <div class="col-md-6 col-12">
                        <div class="card card-info card-outline">
                            <div class="p-3">
                                <div class="">


                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn pl-0 font-weight-bold">Metodo Pago</button>
                                        </div>

                                        <select class="form-control" name="" id="metodo_pago">
                                            <option value="1">De contado</option>
                                            <option value="2">A credito</option>
                                      <!--       <option value="3">Venta en Mercadolibre</option> -->
                                            <!--       <option value="3">Pago a Credito</option> -->
                                        </select>
                                    </div>


                                    <div class="pagoContado" id="pago-contado">
                                        <div class="row mt-4">
                                            <div class="input-group col-md-6">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn pl-0 font-weight-bold">Paga con</button>
                                                </div>
                                                <input type="text" value="" id="cantidad_pagar" class="form-control" placeholder="Cantidad a pagar">
                                            </div>
                                            <div class="input-group col-md-6">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn pl-0 font-weight-bold">Cambio</button>
                                                </div>
                                                <input type="text" id="cantidad_cambio" value="" class="form-control" placeholder="Cambio" readonly>

                                            </div>
                                        </div><!--  -->
                                        <div class="d-flex justify-content-end mt-3">

                                            <button type="button" class="btn bg-secondary text-bold text-light" id="agregar-cliente">AGREGAR CLIENTE</button>

                                        </div>
                                    </div>
                                    <div class="d-none" id="pago-cuotas">
                                        <div class="row mt-4">
                                            <div class="input-group col-md-6">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn pl-0 font-weight-bold">Abono</button>
                                                </div>

                                                <input type="text" value="" id="abono" class="form-control" placeholder="Abono">

                                            </div>
                                            <div class="input-group col-md-6">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn pl-0 font-weight-bold">Saldo</button>
                                                </div>
                                                <input type="text" id="saldo" value="" class="form-control" placeholder="Saldo" readonly>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- valor a pagar y descuentos -->
                    <div class="col-md-6 col-12">
                        <div class="card card-info card-outline">

                            <div class="p-3">

                                <div class="row">
                                  <!--   <div class="input-group col-md-6">
                                        <div class="input-group-prepend">
                                            <button for="" class="btn pl-0 font-weight-bold">Total Sin Comision</button>
                                        </div>

                                        <input id="total_libre" value="0" type="text" name="" class="form-control" readonly>
                                    </div> -->

                                    <div class="input-group col-md-6">
                                        <div class="input-group-prepend">
                                            <button for="total" class="btn pl-0 font-weight-bold">Total Sin descuento</button>
                                        </div>

                                        <input id="total" value="0" type="text" name="" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-group mt-3 col-md-6">
                                        <div class="input-group-prepend">
                                            <button for="descuento" class="btn pl-0 font-weight-bold">Descuento</button>
                                        </div>

                                        <input id="descuento" type="text" value="0%" class="form-control" readonly>
                                    </div>
                                    <div class="input-group mt-3 col-md-6">
                                        <div class="input-group-prepend ">
                                            <button for="total_pagar" class="btn pl-0 font-weight-bold d-block text-right">Total Pagar</button>
                                        </div>

                                        <input id="total_pagar" value="0" type="text" name="" class="form-control">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="button" class="btn bg-azul text-bold  text-light" id="guardar-venta">GUARDAR VENTA</button>
                                </div><!--  -->
                            </div>
                        </div>
                    </div>
                </div>



                <!-- cliente -->

                <div class="card card-info card-outline d-none" id="contenedorCliente">
                    <div class="px-3">
                        <div class="">
                            <div class="pt-2" id="contenedorSlectCliente">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn pl-0 font-weight-bold">Cliente</button>
                                    </div>
                                    <select class="form-control selectCliente select2bs4" id="selectClientes">


                                    </select>
                                </div>
                            </div>
                            <form id="clienteForm" class="px-0 pt-2">

                                <div class="card-body p-0 row">
                                    <div class="col-md-12 row">
                                        <div class="input-group col-md-4">
                                            <div class="input-group-prepend">
                                                <button class="btn pl-0 font-weight-bold" for="nombre_cliente">Nombre</button>
                                            </div>

                                            <input type="text" name="nombre_cliente" class="form-control" id="nombre_cliente" placeholder="Nombre del cliente">
                                        </div>


                                        <div class="input-group col-md-4">
                                            <div class="input-group-prepend">
                                                <button class="btn pl-0 font-weight-bold" for="cedula_cliente">Cédula</button>
                                            </div>

                                            <input type="string" name="cedula_cliente" class="form-control" id="cedula_cliente" placeholder="Número de Cédula">
                                        </div>

                                        <div class="input-group col-md-4">
                                            <div class="input-group-prepend">
                                                <button class="btn pl-0 font-weight-bold" for="celular_cliente">Celular</button>
                                            </div>
                                            <input type="number" name="celular_cliente" class="form-control" id="celular_cliente" placeholder="Número de Celular">
                                        </div>
                                    </div>

                                    <div class="col-md-12 row mt-2">
                                        <div class="input-group col-md-4">
                                            <div class="input-group-prepend">
                                                <button class="btn pl-0 font-weight-bold" for="direccion_cliente">Dirección</button>
                                            </div>
                                            <input type="text" name="direccion_cliente" class="form-control" id="direccion_cliente" placeholder="Dirección del Cliente">
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-prepend">
                                                <button class="btn pl-0 font-weight-bold">Email</button>
                                            </div>
                                            <input type="email_cliente" name="email_cliente" class="form-control" id="email_cliente" placeholder="Email del Cliente">
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <div class="d-flex justify-content-end mt-3">

                                <button type="button" class="btn bg-secondary text-bold text-light" id="quitar-cliente">DESHACER INFORMACIÓN</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>