<script src="view/assets/vendor/dropzone/dropzone-min.js"></script>
<link href="view/assets/vendor/dropzone/dropzone.css" rel="stylesheet">
<?php
    $schools = FormsController::ctrSearchschools(null, null);
?>

<!-- Formulario para añadir nuevos pisos -->
<form class="my-3 newFloorForm" id="newFloorForm">
    <div class="form-group">
        <label for="floorName">Nombre del Piso</label>
        <input type="text" class="form-control" id="floorName" placeholder="Nombre del piso">
    </div>
    <div class="d-flex justify-content-center my-3">
        <button type="button" class="btn btn-success saveNewFloor" disabled>Aceptar</button>
    </div>
</form>