<?php include_once __DIR__ . '/../templates/content-header.php'; ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 my-2">
         
                    

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="btn pl-0 font-weight-bold">Selecciona una fecha:</button>
                        </div>
                        <input type="date" class="form-control" id="fecha" name="fecha">
                     
                    </div>
             
            </div>
        </div>


    </div>
</section>


<section class="content" id="reporte">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer">
                         <div class="row">
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="reporte_total_ventas">$0</h5>
                                    <span class="description-text">Ventas totales</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="reporte_total_recaudos">$0</h5>
                                    <span class="description-text">RECAUDOS TOTALES</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="reporte_costos">$0</h5>
                                    <span class="description-text">COSTOS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="reporte_ganancia_no_realizada">$0</h5>
                                    <span class="description-text">GANANCIAS NO REALIZADAS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="reporte_ganancia_realizada">$0</h5>
                                    <span class="description-text">GANANCIAS REALIZADAS</span>
                                </div>
                            </div>
                        
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="reporte_inventario">$0</h5>
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

                                    <h5 class="description-header" id="reporte_ingresos_reales">$0</h5>
                                    <span class="description-text">INGRESOS SiN FIADOS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="reporte_ganancias_reales">$0</h5>
                                    <span class="description-text">GANANCIAS SIN FIADOS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">

                                    <h5 class="description-header" id="reporte_dinero_fiados">$0</h5>
                                    <span class="description-text">FIADOS</span>
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
                                <span class="info-box-number" id="reporte_numero_ventas">
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
                                <span class="info-box-text">Fiados</span>
                                <span class="info-box-number" id="reporte_numero_fiados">
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
                                <span class="info-box-number" id="reporte_numero_pagos">0</span>
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
                                <span class="info-box-number" id="reporte_numero_cajas">0</span>
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
                                <span class="info-box-number" id="reporte_numero_productos">0</span>
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
                                <span class="info-box-number" id="reporte_numero_clientes">0</span>
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

<!-- <div class="content-header py-1">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-xl">Gráficos</h1>
            </div>

        </div>
    </div>
</div>

<section class="content" id="reportes">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Ingresos Últimos 7 días</h3>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">820</span>
                                <span>ingresos</span>
                            </p>

                        </div>
                        
                        <div class="position-relative mb-4">
                            <canvas id="ultima-semana" height="200"></canvas>
                        </div>

                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> Últimos 7 días
                            </span>


                        </div>
                    </div>
                </div>

            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Online Store Visitors</h3>
                            <a href="javascript:void(0);">View Report</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">820</span>
                                <span>Visitors Over Time</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i> 12.5%
                                </span>
                                <span class="text-muted">Since last week</span>
                            </p>
                        </div>
               

                        <div class="position-relative mb-4">
                            <canvas id="ultimo-mes" height="200"></canvas>
                        </div>

                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> This Week
                            </span>

                            <span>
                                <i class="fas fa-square text-gray"></i> Last Week
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Ingresos de los Últimos 12 meses</h3>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="text-bold text-lg">$18,230.00</span>
                                <span>Ingresos</span>
                            </p>

                        </div>
                     

                        <div class="position-relative mb-4">
                            <canvas id="ultimo-year" height="300"></canvas>
                        </div>

                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                                <i class="fas fa-square text-primary"></i> Último año
                            </span>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<!-- /.card -->


<!-- /.card -->