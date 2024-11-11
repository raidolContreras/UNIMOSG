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
    <h5 id="namePage" class="page-title"></h5>
</div>
<div class="col-md-12 col-lg-9 col-xl-10 p-4 row" style="width: 100vw; height: 58vh; margin: 0; padding: 0; top: 0; left: 0;">

  <div class="col-12">
      <div class="p-3">
          <div class="d-flex items-center ms-auto mt-3 mt-sm-0">
              <button class="btn btn-secondary" onclick="openMenu('modalEdifices', 'newEdifices')"><i class="fa-duotone fa-plus"></i>Registrar edificio</button>
          </div>
      </div>
      
      <div id="edificesContainer" class="edifices-container row"></div>
  </div>

</div>
<input type="hidden" id="school" value="<?php echo (isset($_GET['school']) ? $_GET['school'] :0) ?>">

<div class="modal-collapse" id="modalEdifices">
    <span class="close-btn" onclick="closeMenu('modalEdifices')">&times;</span>
    <div class="modal-nav">
        <?php include "view/pages/admin/edifices/newEdifices.php"; ?>
    </div>
</div>

<div class="modal-collapse" id="modalNavUpdate">
    <span class="close-btn" onclick="closeMenu('modalNavUpdate')">&times;</span>
    <div class="modal-nav">
        <?php include "view/pages/admin/edifices/editEdifices.php"; ?>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="deleteEdificers" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Eliminar Escuela</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Esta usted seguro que desea eliminar el edificio?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success delete">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script src="view/assets/js/ajax/Edifices/getEdifices.js"></script>
