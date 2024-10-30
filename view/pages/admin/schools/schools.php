<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>

<div class="card-custom">
    <div class="card-header-custom">
        <strong id='namePage'></strong>
    </div>
</div>
<div class="col-12">
<main class="container">
    <div class="p-3">
        <div class="d-flex items-center ms-auto mt-3 mt-sm-0">
            <button class="btn btn-secondary" onclick="openMenu('modalCollapse', 'newSchools')"><i class="fa-duotone fa-plus"></i> Registrar plantel</button>
        </div>
    </div>
	
    <table id="schoolsActive" class="table table-resposive stripe">
        <thead>
            <th>#</th>
            <th class="">Nombre del plantel</th>
            <th width="30%"></th>
        </thead>
    </table>
</main>
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