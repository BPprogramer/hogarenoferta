<?php include_once __DIR__ . '/../templates/content-header.php'; ?>



<!-- Main content -->
<section class="content" id="ventas">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <!-- /.card -->

                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between">
                            <div class="col-4">
                                <h3 class="card-title">Ventas</h3>
                            </div>
                            <div class="col-4 d-flex justify-content-end">
                                <a href="/crear-venta" class="btn bg-hover-azul text-white toolMio">
                                    Agregar Venta
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabla" class="display responsive table w-100 table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CODIGO</th>
                                    <th>TOTAL</th>
                                    <th>RECUADO</th>

                                    <th>ESTADO</th>
                                    <th>No CAJA</th>
                                    <th>FECHA</th>
                                    <th class="">Acciones</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>



<div class="modal fade" id="modal-producto">
    <div class="modal-dialog modal-50rem">
        <div class="modal-content">
            <div class="modal-header bg-azul">
                <h4 class="modal-title text-white">Registrar Producto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="productoForm">

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre del Producto">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="codigo">Código</label>
                                <input type="text" name="codigo" class="form-control" id="codigo" placeholder="Código del Producto">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="categoria_id">Categoria del producto</label>
                                <select class="form-control selectCategoria" id="categoria_id" style="width: 100%;">


                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="provedor_id">Proveedor del Producto</label>
                                <select class="form-control selectProveedor" id="proveedor_id" style="width: 100%;">


                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="stock">Stock</label>
                                <input type="number" name="stock" class="form-control" id="stock" placeholder="Stock con el que inicia el producto">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="stock_minimo">Stock Mínimo</label>
                                <input type="number" name="stock_minimo" class="form-control" id="stock_minimo" placeholder="Stock mínimo en inventario">
                            </div>
                        </div>
                        <hr class="bg-azul">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="precio_paquete">Precio paquete</label>
                                <input type="text" name="precio_paquete" class="form-control" id="precio_paquete" placeholder="Precio del paquete completo">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="unidades"> Unidades por paquete</label>
                                <input type="number" name="unidades" class="form-control" id="unidades" placeholder="Unidades por paquete">
                            </div>
                        </div>
                        <hr class="bg-azul">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="precio_compra">Precio de compra</label>
                                <input type="text" name="precio_compra" class="form-control" id="precio_compra" placeholder="Precio de Compra">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="precio_venta">Precio de venta</label>
                                <input type="text" name="precio_venta" class="form-control" id="precio_venta" placeholder="Precio de venta">
                            </div>
                        </div>
                        <div class="row justify-content-end">

                            <div class="form-group col-md-6">
                                <label for="porcentaje_venta">Porcentaje para precio de venta</label>
                                <input type="number" name="porcentaje_venta" class="form-control" id="porcentaje_venta" placeholder="Calcular el precio de venta con porcentaje">
                            </div>
                        </div>
                    </div>



                    <div class="card-footer">
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                            <div class="">
                                <button type="submit" class="btn bg-hover-azul text-white" id="btnSubmit">Enviar</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Modal para actualizar stock -->

<div class="modal fade" id="modal-stock">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-azul">
                <h4 class="modal-title text-white">Actualizar Stock</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="stockForm">

                    <div class="card-body">

                        <div class="form-group">
                            <label for="nombre_producto">Nombre del Proucto</label>
                            <input type="text" name="nombre_producto" class="form-control" readonly id="nombre_producto">
                        </div>




                        <div class="form-group">
                            <label for="nuevo_stock">Cantidad Adquirida</label>
                            <input type="number" name="nuevo_stock" class="form-control" id="nuevo_stock" placeholder="Digite la cantidad adquirida">
                        </div>


                        <hr class="bg-azul">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="precio_paquete_nuevo">Precio paquete</label>
                                <input type="text" name="precio_paquete_nuevo" class="form-control" id="precio_paquete_nuevo" placeholder="Precio del paquete completo">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="unidades_nuevo"> Unidades por paquete</label>
                                <input type="number" name="unidades_nuevo" class="form-control" id="unidades_nuevo" placeholder="Unidades por paquete">
                            </div>
                        </div>
                        <hr class="bg-azul">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="precio_compra_nuevo">Precio de compra</label>
                                <input type="text" name="precio_compra_nuevo" class="form-control" id="precio_compra_nuevo" placeholder="Precio de Compra">
                            </div>

                        </div>

                    </div>



                    <div class="card-footer">
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                            <div class="">
                                <button type="submit" class="btn bg-hover-azul text-white" id="btnSubmitNewStock">Actualizar</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-info">
    <div class="modal-dialog modal-70rem">
        <div class="modal-content">
            <div class="modal-header bg-azul">
                <h4 class="modal-title text-white mx-3">Información</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body mx-3">

                <!-- informacion general -->
                <div class="row">
                    <div class="col-md-6 pr-4">
                        <div class="d-flex justify-content-between  border-bottom">
                            <p class="my-2">Venta No: </p>
                            <p class="my-2"><strong class="text-danger" id="codigo-venta"></strong></p>
                        </div>


                    </div>
                    <div class="col-md-6 pl-4">
                        <div class="d-flex justify-content-between border-bottom">
                            <p class="my-2">Total: </p>
                            <p class="my-2"><strong class="" id="total-venta"></strong></p>
                        </div>
                    </div>

                    <div class="col-md-6 pr-4">
                        <div class="d-flex justify-content-between  border-bottom">
                            <p class="my-2">Cliente: </p>
                            <p class="my-2"><strong class="" id="cliente-venta"></strong></p>
                        </div>
                    </div>
                    <div class="col-md-6 pl-4">
                        <div class="d-flex justify-content-between border-bottom">
                            <p class="my-2">Abono: </p>
                            <p class="my-2"><strong class="" id="recaudo-venta"></strong></p>
                        </div>
                    </div>

                    <div class="col-md-6  pr-4">
                        <div class="d-flex justify-content-between border-bottom">
                            <p class="my-2">Fecha: </p>
                            <p class="my-2"><strong class="" id="fecha-venta"></strong></p>
                        </div>
                    </div>
                    <div class="col-md-6 pl-4">
                        <div class="d-flex justify-content-between  border-bottom">
                            <p class="my-2">Saldo </p>
                            <p class="my-2"><strong class="text-danger" id="saldo-venta"></strong></p>
                        </div>


                    </div>
                    <div class="col-md-6 pr-4">

                        <div class="d-flex justify-content-between  border-bottom">
                            <p class="my-2">Estado </p>
                            <p class="my-2"><strong class="" id="estado-venta"></strong></p>
                        </div>

                    </div>
                    <div class="col-md-6 pl-4">
                        <div class="d-flex justify-content-between  border-bottom">
                            <p class="my-2">metodo de pago </p>
                            <p class="my-2"><strong class="" id="metodo-venta"></strong></p>
                        </div>


                    </div>
                    <div class="col-md-6 pr-4">
                        <div class="d-flex justify-content-between  border-bottom">
                            <p class="my-2">Total Sin descuento </p>
                            <p class="my-2"><strong class="" id="total"></strong></p>
                        </div>

                    </div>
                    <div class="col-md-6 pl-4">

                        <div class="d-flex justify-content-between  border-bottom">
                            <p class="my-2">Descuento </p>
                            <p class="my-2"><strong class="" id="total-sin-descuento"></strong></p>
                        </div>

                    </div>




                </div>

                <!-- informacion de los productos -->

                <div class="mt-4">

                    <table class="table table-striped text-md" id="tabla-productos-fiados">
                        <thead class="thead">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>precio unitario</th>
                                <th>subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="body-productos-venta">
                            <!--    <tr>
                                    <td>Ron Viejo de Caldas</td>
                                    <td>2</td>
                                    <td>25,000</td>
                                    <td>50,000</td>
                                </tr> -->
                        </tbody>
                    </table>

                </div>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>