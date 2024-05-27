<div class="card-custom">
    <div class="card-header-custom">
        <strong id='namePage'></strong>
    </div>
</div>
<div class="col-12">
<main class="container">
    <div class="p-3">
        <div class="d-flex items-center ms-auto mt-3 mt-sm-0">
            <button class="btn btn-secondary" onclick="openMenu('modalAreas', 'newAreas')"><i class="fa-duotone fa-plus"></i> Registrar areas</button>
        </div>
    </div>
	
    <table id="areas" class="table table-resposive stripe">
        <thead>
            <th>#</th>
            <th class="">Nombre del area</th>
            <th class="">Zona</th>
            <th class="">Escuela</th>
            <th width="30%"></th>
        </thead>
    </table>
</main>
</div>
<input type="hidden" id="zone" value="<?php echo $_POST['zone'] ?>">

<div class="modal-collapse" id="modalAreas">
    <span class="close-btn" onclick="closeMenu('modalAreas')">&times;</span>
    <ul class="modal-nav">
        <?php include "view/pages/admin/areas/addAreas.php"; ?>
    </ul>
</div>

<div class="modal-collapse" id="modalObjects">
    <span class="close-btn" onclick="closeMenu('modalObjects')">&times;</span>
    <ul class="modal-nav">
        <?php include "view/pages/admin/areas/addObjects.php"; ?>
    </ul>
</div>

<div class="modal-collapse" id="modalNavUpdate">
    <span class="close-btn" onclick="closeMenu('modalNavUpdate')">&times;</span>
    <ul class="modal-nav">
        <?php include "view/pages/admin/areas/editAreas.php"; ?>
    </ul>
</div>


<script src="view/assets/js/ajax/getAreas.js"></script>