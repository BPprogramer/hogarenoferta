<?php include_once __DIR__ . '/../templates/content-header.php'; ?>
<div class="content" id="seccion-fiados">
    <div class="container-fluid">

        <div class="container-fluid mb-2 px-0" id="">

            <div class="row d-flex justify-content-between align-items-center px-3">
                <div class="col-sm-5">
                    <div class="input-group m-2 ">
                        <div class="input-group-prepend">
                            <button type="button" class="btn pl-0 font-weight-bold">Cliente</button>
                        </div>
                        <select class="form-control  select2bs4" id="selectClientes">

                        </select>
                    </div>
                </div>


                <div class="col-sm-4">
                    <p class="text-lg mb-0">Deuda Total: <strong class="text-xl text-danger">$</strong><strong
                            class="text-danger text-xl" id="total-deuda">0</strong></p>
                </div>
                <div class="" id="">
                    <button type="button" id="pagar" class="btn bg-hover-azul text-white  text-bold">
                        PAGAR

                    </button>
                </div>


            </div>
        </div>



        <div class="card card-info card-outline" id="contenedor-deudas">
            <div class="px-3">
                <div class="row py-2 d-flex justify-content-between">
                    <div class="col">
                        <h3 class="">Deudas</h3>
                    </div>

                    <div class="col d-flex justify-content-end">
                        <!--  <button type="button" id="registrar" class="btn bg-hover-azul text-white text-bold">
                            VER TODO

                        </button> -->
                    </div>
                </div>

                <div class="tabla">
                    <table class="table table-striped text-md" id="tabla-fiados">
                        <thead class="thead">
                            <tr>
                                <th>Venta</th>
                                <th>Total</th>
                                <th>Recaudo</th>
                                <th>Deuda</th>
                                <th>Estado</th>
                                <th>Detalles</th>

                            </tr>
                        </thead>
                        <tbody id="body-fiados">


                        </tbody>

                    </table>

                </div>

            </div>


        </div>
     
    </div>
</div>

<div class="modal fade" id="modal-pagos">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-azul">
                <h4 class="modal-title text-white ">Pagos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="tabla">
                    <table class="table table-striped text-md" id="tabla-fiados">
                        <thead class="thead">
                            <tr>
                                <th># Pago</th>
                                <th>Fecha</th>
                                <th>Responsable</th>
                                <th>Monto</th>
                                <th>Saldo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="body-pagos">

                        </tbody>
                    </table>

                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>



<div class="modal fade" id="modal-pago">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-azul">
                <h4 class="modal-title text-white ">Pagar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="pagoForm">

                    <div class="card-body">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn font-weight-bold pl-0">Deuda</button>
                            </div>
                            <input type="text" value="" id="deuda-actual" class="form-control" readonly>
                        </div>
                        <div class="input-grou mt-3">
                            <div class="input-group-prepend">
                                <button type="button" id="pagar-todo"
                                    class="btn bg-azul text-blanco font-weight-bold">PAGAR TODO</button>
                            </div>
                        </div>
                        <div class="input-group mt-3">
                            <div class="input-group-prepend">
                                <button type="button" class="btn  font-weight-bold pl-0">Monto</button>
                            </div>
                            <input type="text" name="monto" class="form-control" id="monto" placeholder="Monto a Pagar">
                        </div>
                        <div class="mt-3 text-lg">
                            <p><strong>Saldo:</strong> <span class="text-danger" id="saldo-restante">$0</span></p>
                        </div>




                    </div>

                    <div class="card-footer">
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                            <div class="">
                                <button type="submit" class="btn bg-hover-azul text-white" id="btnSubmitPago">Enviar</button>
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


<div class="modal  fade" id="modal-info">
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
                            <p class="my-2"><strong class="text-danger" id="codigo-venta">1006</strong></p>
                        </div>


                    </div>
                    <div class="col-md-6 pl-4">
                        <div class="d-flex justify-content-between border-bottom">
                            <p class="my-2">Total: </p>
                            <p class="my-2"><strong class="" id="total-venta">50,000</strong></p>
                        </div>
                    </div>

                    <div class="col-md-6 pr-4">
                        <div class="d-flex justify-content-between  border-bottom">
                            <p class="my-2">Cliente: </p>
                            <p class="my-2"><strong class="" id="cliente-venta">Hair Acosta</strong></p>
                        </div>
                    </div>
                    <div class="col-md-6 pl-4">
                        <div class="d-flex justify-content-between border-bottom">
                            <p class="my-2">Abono: </p>
                            <p class="my-2"><strong class="" id="recaudo-venta">20,000</strong></p>
                        </div>
                    </div>

                    <div class="col-md-6  pr-4">
                        <div class="d-flex justify-content-between border-bottom">
                            <p class="my-2">Fecha: </p>
                            <p class="my-2"><strong class="" id="fecha-venta">2023-02-05</strong></p>
                        </div>
                    </div>
                    <div class="col-md-6 pl-4">
                        <div class="d-flex justify-content-between  border-bottom">
                            <p class="my-2">Saldo </p>
                            <p class="my-2"><strong class="text-danger" id="saldo-venta">50,000</strong></p>
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
                        <tbody id="body-productos-fiados">
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