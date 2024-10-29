<script src="view/assets/vendor/dropzone/dropzone-min.js"></script>
<link href="view/assets/vendor/dropzone/dropzone.css" rel="stylesheet">

<div class="intro-y box d-none" id="massiveAreasForm">
    <form class="p-1" id="newAreas">
        <center>
            <h3 class="nameschool"></h3>
        </center>
        <!-- Botón para descargar plantilla de ejemplo -->
        <div class="form-group my-3">
            <a type="button" class="btn btn-link" download="areas_template.csv" href="view/assets/templates/areas_template.csv">Descargar plantilla</a>
        </div>
        <!-- Dropzone para cargar archivos -->
        <div class="col-md-12">
            <div id="addAreasDropzone" class="dropzone"></div>
        </div>
        <input type="hidden" class="idReference" id="idZone" value="<?php echo $_POST['zone'] ?>">
        
        <div class="d-flex justify-content-center my-3">
            <button type="button" class="btn btn-danger me-2 cancelMassiveAreas">Cancelar</button>
            <button type="button" class="btn btn-success sendAreas" disabled>Aceptar</button>
        </div>
    </form>
</div>

<!-- Formulario para añadir nuevos objetos -->
<form id="newAreaForm" class="my-3">
    <div class="form-group">
        <label for="areaName">Nombre del area</label>
        <input type="text" class="form-control" id="areaName" placeholder="Ingrese el nombre del area">
    </div>
    <div class="d-flex justify-content-center my-3">
        <button type="button" class="btn btn-success saveNewArea" disabled>Aceptar</button>
    </div>
</form>

<div class="d-grid gap-2">
    <div class="btn-group addAreas">
        <button type="button" class="btn btn-link my-3 addMassiveAreas"><i class="fad fa-folder-plus"></i> Añadir areas</button>
    </div>
</div>

<script src="view/assets/js/ajax/addAreas.js"></script>
