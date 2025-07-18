<?php include_once __DIR__ . '/../templates/content-header.php'; ?>

<section class="content" id="inicio">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="inicio_total_ventas">$0</h5>
                                    <span class="description-text">Ventas totales</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="inicio_total_recaudos">$0</h5>
                                    <span class="description-text">RECAUDOS TOTALES</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="inicio_costos">$0</h5>
                                    <span class="description-text">COSTOS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="inicio_ganancia_no_realizada">$0</h5>
                                    <span class="description-text">GANANCIAS NO REALIZADAS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="inicio_ganancia_realizada">$0</h5>
                                    <span class="description-text">GANANCIAS REALIZADAS</span>
                                </div>
                            </div>
                        
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="inicio_inventario">$0</h5>
                                    <span class="description-text">EN INVENTARIO</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--  <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="inicio_ingresos_reales">$0</h5>
                                    <span class="description-text">INGRESOS SiN FIADOS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header"  id="inicio_ganancias_reales">$0</h5>
                                    <span class="description-text">GANANCIAS SIN FIADOS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header"  id="inicio_dinero_fiados">$0</h5>
                                    <span class="description-text">FIADOS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header"  id="inicio_dinero_mercado_libre">$0</h5>
                                    <span class="description-text">Mercado Libre</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header"  id="inicio_dinero_pendiente_mercado_libre">$0</h5>
                                    <span class="description-text">Mercado Libre Pendientes</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div> -->
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon text-white bg-azul elevation-1"><i class="fas fa-shopping-cart"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Ventas Pagadas</span>
                                <span class="info-box-number" id="inicio_numero_ventas">
                                    0

                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon text-white bg-azul elevation-1"><i class="fas fa-hand-holding-usd"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Ventas Pendientes</span>
                                <span class="info-box-number" id="inicio_numero_fiados">
                                    0

                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon text-white bg-azul elevation-1"><i class="fa fa-credit-card"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Pagos</span>
                                <span class="info-box-number" id="inicio_numero_pagos">0</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon text-white bg-azul elevation-1"><i class="fa-solid fa-cash-register"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Cajas</span>
                                <span class="info-box-number" id="inicio_numero_cajas">0</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon text-white bg-azul elevation-1"><i class="fa-brands fa-product-hunt"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Productos</span>
                                <span class="info-box-number" id="inicio_numero_productos">0</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon text-white bg-azul elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Clientes</span>
                                <span class="info-box-number" id="inicio_numero_clientes">0</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </div>
    </div>
</section>