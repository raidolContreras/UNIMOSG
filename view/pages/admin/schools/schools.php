<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

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
    <main class="container">
        <div class="p-3">
            <div class="d-flex items-center ms-auto mt-3 mt-sm-0">
                <button class="btn btn-secondary" onclick="openMenu('modalCollapse', 'newSchools')"><i class="fa-duotone fa-plus"></i> Registrar plantel</button>
            </div>
        </div>
        
        <div id="schoolsContainer" class="schools-container row"></div>
    </main>
  </div>

</div>

<div class="modal-collapse" id="modalCollapse">
    <span class="close-btn" onclick="closeMenu('modalCollapse')">&times;</span>
    <div class="modal-nav">
        <?php include "view/pages/admin/schools/newSchool.php"; ?>
    </div>
</div>

<div class="modal-collapse" id="modalZones">
    <span class="close-btn" onclick="closeMenu('modalZones')">&times;</span>
    <div class="modal-nav">
        <?php include "view/pages/admin/schools/addZones.php"; ?>
    </div>
</div>

<div class="modal-collapse" id="modalNavUpdate">
    <span class="close-btn" onclick="closeMenu('modalNavUpdate')">&times;</span>
    <div class="modal-nav">
        <?php include "view/pages/admin/schools/editSchool.php"; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteSchool" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Eliminar Escuela</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Esta usted seguro que desea eliminar la escuela?
        <input type="hidden" id="idSchool">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="deleteSchool()">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script src="view/assets/js/ajax/Schools/getSchools.js"></script>