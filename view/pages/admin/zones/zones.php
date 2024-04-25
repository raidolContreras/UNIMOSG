<div class="card-custom">
    <div class="card-header-custom">
        <strong id='namePage'></strong>
    </div>
</div>
<div class="col-12">
    <div class="p-3">
        <div class="d-flex items-center ms-auto mt-3 mt-sm-0">
            <button class="btn btn-success" onclick="openMenu('modalNav', 'newAreas')">Registrar areas</button>
        </div>
    </div>
    <table id="zones" class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre de la zona</th>
                <th>Escuela a la que pertenece</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<div class="modal-collapse" id="modalNav">
    <span class="close-btn" onclick="closeMenu('modalNav')">&times;</span>
    <ul class="modal-nav">
        <?php include "view/pages/admin/zones/newZones.php"; ?>
    </ul>
</div>

<script src="view/assets/js/ajax/getZones.js"></script>
