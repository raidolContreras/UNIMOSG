<script src="view/assets/vendor/dropzone/dropzone-min.js"></script>
<link href="view/assets/vendor/dropzone/dropzone.css" rel="stylesheet">
<?php
    $schools = FormsController::ctrSearchschools(null, null);
?>

<!-- Formulario para añadir nuevas zonas -->
<form class="my-3 newEdificeForm" id="newEdificeForm">
    <div class="form-group">
        <label for="edificeName">Nombre del edificio</label>
        <input type="text" class="form-control" id="edificeName" placeholder="Nombre del edificio">
    </div>
    <div class="d-flex justify-content-center my-3">
        <button type="button" class="btn btn-success saveNewEdifice" disabled>Aceptar</button>
    </div>
</form>

<div class="intro-y box d-none moduleAddEdifices">
    <form class="p-1" id="newEdifices">
        <center>
            <h3 class="nameschool"></h3>
        </center>
        <!-- Botón para descargar plantilla de ejemplo -->
        <div class="form-group my-3">
            <a type="button" class="btn btn-link" download="edifices_template.csv" href="view/assets/templates/edifices_template.csv">Descargar plantilla</a>
        </div>
        <!-- Dropzone para cargar archivos -->
        <div class="col-md-12">
            <div id="addEdificesDropzone" class="dropzone"></div>
        </div>
        <select class="form-select mt-3" name="idSchool" id="idSchool">
            <option value="" disabled selected>Seleccione una escuela</option>
            <?php foreach ($schools as $school) :?>
                <option value="<?php echo $school['idSchool'];?>"><?php echo $school['nameSchool'];?></option>
            <?php endforeach;?>
        </select>
        <input type="hidden" id="edifice" value="1">
        
        <div class="d-flex justify-content-center my-3">
            <button type="button" class="btn btn-danger me-2 cancelMassiveEdifices">Cancelar</button>
            <button type="button" class="btn btn-success sendEdifices" disabled>Aceptar</button>
        </div>
    </form>
</div>

<div class="d-grid gap-2 addEdifices">
    <div class="btn-group ">
        <button type="button" class="btn btn-link my-3 addMassiveEdifice"><i class="fad fa-folder-plus"></i> Registro masivo con archivo</button>
    </div>
</div>

<script src="view/assets/js/ajax/Edifices/addEdifices.js"></script>
