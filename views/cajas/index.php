<?php include_once __DIR__ . '/../templates/content-header.php'; ?>



<!-- Main content -->
<section class="content" id="cajas">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <!-- /.card -->

        <div class="card">
          <div class="card-header">
            <div class="row justify-content-between">
              <div class="col-4">
                <h3 class="card-title">Cajas</h3>
              </div>
              <div class="col-4 d-flex justify-content-end">
                <button type="button" id="registrar" class="btn bg-hover-azul text-white toolMio">
                    Abrir Caja
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
                  <th>No Caja</th>
                  <th>RESPONSABLE</th>
                  <th>EFECTIVO INICIAL</th>
                  <th>TOTAL RECAUDO</th>
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



<div class="modal fade" id="modal-caja">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-azul">
        <h4 class="modal-title text-white">Abrir Caja</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="cajaForm">

          <div class="card-body">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="efectivo_apertura">Efectivo Inicial</label>
                <input type="text" name="efectivo_apertura" class="form-control" id="efectivo-apertura" placeholder="Efectivo inicial de la Caja">
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

