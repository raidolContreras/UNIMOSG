<div class="card-custom">
    <div class="card-header-custom">
        <strong id='namePage'></strong>
    </div>
</div>
<div class="col-12">
<main class="container">
    <div class="p-3">
        <div class="d-flex items-center ms-auto mt-3 mt-sm-0">
            <button class="btn btn-secondary" onclick="openMenu('modalCollapse', 'newSchools')"><i class="fa-duotone fa-plus"></i> Registrar escuela</button>
        </div>
    </div>
	
    <table id="schools" class="table table-resposive stripe">
        <thead>
            <th>#</th>
            <th class="">Nombre de la escuela</th>
            <th width="30%"></th>
        </thead>
    </table>
</main>
</div>

<div class="modal-collapse" id="modalCollapse">
    <span class="close-btn" onclick="closeMenu('modalCollapse')">&times;</span>
    <ul class="modal-nav">
        <?php include "view/pages/admin/schools/newSchool.php"; ?>
    </ul>
</div>

<div class="modal-collapse" id="modalZones">
    <span class="close-btn" onclick="closeMenu('modalZones')">&times;</span>
    <ul class="modal-nav">
        <?php include "view/pages/admin/schools/addZones.php"; ?>
    </ul>
</div>

<div class="modal-collapse" id="modalNavUpdate">
    <span class="close-btn" onclick="closeMenu('modalNavUpdate')">&times;</span>
    <ul class="modal-nav">
        <?php include "view/pages/admin/schools/editSchool.php"; ?>
    </ul>
</div>

<script src="view/assets/js/ajax/getSchools.js"></script>