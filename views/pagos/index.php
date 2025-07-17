<?php include_once __DIR__ . '/../templates/content-header.php'; ?>



<!-- Main content -->
<section class="content" id="pagos">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <!-- /.card -->

        <div class="card">
          <div class="card-header">
            <div class="row justify-content-between">
              <div class="col-4">
                <h3 class="card-title">Pagos</h3>
              </div>
              <div class="col-4 d-flex justify-content-end">
                <!--  <button type="button" id="registrar" class="btn bg-hover-azul text-white toolMio">
                  Registrar Producto
                </button> -->
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="tabla" class="display responsive table w-100 table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>No de pago</th>
                  <th>No de Venta</th>
                  <th>No Caja</th>
                  <th>Monto</th>
                  <th>Saldo</th>

                  <!--       <th>No venta</th> -->
                  <th>Fecha</th>

                  <!-- 
                  <th class="text-center">Acciones</th> -->
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