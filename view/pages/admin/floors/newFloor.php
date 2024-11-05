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

<div class="intro-y box d-none moduleAddFloors">
    <form class="p-1" id="newFloors">
        <center>
            <h3 class="nameschool"></h3>
        </center>
        <!-- Botón para descargar plantilla de ejemplo -->
        <div class="form-group my-3">
            <a type="button" class="btn btn-link" download="floors_template.csv" href="view/assets/templates/floors_template.csv">Descargar plantilla</a>
        </div>
        <!-- Dropzone para cargar archivos -->
        <div class="col-md-12">
            <div id="addFloorsDropzone" class="dropzone"></div>
        </div>
        <input type="hidden" id="floor" value="1">
        
        <div class="d-flex justify-content-center my-3">
            <button type="button" class="btn btn-danger me-2 cancelMassiveFloors">Cancelar</button>
            <button type="button" class="btn btn-success sendFloors" disabled>Aceptar</button>
        </div>
    </form>
</div>

<div class="d-grid gap-2 addFloors">
    <div class="btn-group">
        <button type="button" class="btn btn-link my-3 addMassiveFloor"><i class="fad fa-folder-plus"></i> Registro masivo con archivo</button>
    </div>
</div>
