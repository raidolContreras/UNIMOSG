<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<style>
  .card-body {
    margin-top: 0px !important;
  }

  .dropdown-menu {
    position: absolute !important;
    z-index: 9999 !important; /* Prueba con valores más altos si es necesario */
    top: 100%; /* Esto asegurará que aparezca debajo del botón */
    left: 0; /* Alinear a la izquierda */
  }
  .card.school-item {
    position: relative;
  }

</style>

<div class="p-3 mb-4 rounded">
    <h5 id="namePage" class="page-title">Gestión de Pisos</h5>
</div>
<div class="col-md-12 col-lg-9 col-xl-10 p-4 row" style="width: 100vw; height: 58vh; margin: 0; padding: 0; top: 0; left: 0;">

  <div class="col-12">
      <div class="p-3">
          <div class="d-flex items-center ms-auto mt-3 mt-sm-0">
              <button class="btn btn-primary" onclick="openMenu('modalFloors', 'newFloors')">
                  <i class="fa-duotone fa-plus"></i> Registrar Piso
              </button>
          </div>
      </div>
      
      <div id="floorsContainer" class="floors-container row"></div>
      
  </div>
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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">&times;</button>
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
