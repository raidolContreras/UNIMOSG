<div class="p-3 mb-4 rounded">
    <h5 id="namePage" class="page-title">Gestión de Pisos</h5>
</div>
<div class="col-12">
    <div class="p-3">
        <div class="d-flex items-center ms-auto mt-3 mt-sm-0">
            <button class="btn btn-primary" onclick="openMenu('modalFloors', 'newFloors')">
                <i class="fa-duotone fa-plus"></i> Registrar Piso
            </button>
        </div>
    </div>
    <table id="floors" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre del Piso</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>
</div>
<input type="hidden" id="edificer" value="<?php echo (isset($_GET['edificer']) ? $_GET['edificer'] : 0); ?>">

<div class="modal-collapse" id="modalFloors">
    <span class="close-btn" onclick="closeMenu('modalFloors')">
        <i class="fas fa-times"></i>
    </span>
    <div class="modal-nav">
        <?php include "view/pages/admin/floors/newFloor.php"; ?>
    </div>
</div>

<div class="modal-collapse" id="modalNavUpdate">
    <span class="close-btn" onclick="closeMenu('modalNavUpdate')">
        <i class="fas fa-times"></i>
    </span>
    <div class="modal-nav">
        <?php include "view/pages/admin/floors/editFloor.php"; ?>
    </div>
</div>

<!-- Modal for Deleting Floors -->
<div class="modal fade" id="deleteFloors" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Eliminar Piso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Está seguro de que desea eliminar este piso?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-outline-success delete">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script src="view/assets/js/ajax/Floors/getFloors.js"></script>
