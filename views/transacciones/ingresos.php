<?php include_once __DIR__ . '/../templates/content-header.php'; ?>



<!-- Main content -->
<section class="content" id="ingresos">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <!-- /.card -->

        <div class="card">
          <div class="card-header">
            <div class="row justify-content-between">
              <div class="col-4">
                <h3 class="card-title">Ingresos</h3>
              </div>
              <div class="col-4 d-flex justify-content-end">
                <button type="button" id="registrar" class="btn bg-hover-azul text-white toolMio">
                    Registrar Ingreso
                </button>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="tabla" class="display responsive table w-100 table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>RESPONSABLE</th>
                  <th>FECHA</th>
                  <th>MONTO</th>
                  <th>No CAJA</th>
                  <th>Descripcion</th>
                  <th>ESTADO</th>
                  <th class="text-center">Acciones</th>
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



<div class="modal fade" id="modal-ingreso">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-azul">
        <h4 class="modal-title text-white">Registrar Ingreso</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ingresoForm">

          <div class="card-body">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="ingreso">Monto</label>
                <input type="text" name="ingreso" class="form-control" id="ingreso" placeholder="Monto del ingreso">
              </div>
              <div class="form-group col-md-12">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control"   cols="30" rows="10" name="descripcion" id="descripcion" placeholder="ej: Para regresos"></textarea>
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

