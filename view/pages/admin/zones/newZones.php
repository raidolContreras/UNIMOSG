<script src="view/assets/vendor/dropzone/dropzone-min.js"></script>
<link href="view/assets/vendor/dropzone/dropzone.css" rel="stylesheet">
<?php
    $schools = FormsController::ctrSearchschools(null, null);
?>

<div class="intro-y box">
    <form class="p-5" id="newZones">
        <center>
            <h3 class="nameschool"></h3>
        </center>
        <!-- Botón para descargar plantilla de ejemplo -->
        <div class="form-group my-3">
            <a type="button" class="btn btn-link" download="zones_template.csv" href="view/assets/templates/zones_template.csv">Descargar plantilla</a>
        </div>
        <!-- Dropzone para cargar archivos -->
        <div class="col-md-12">
            <div id="addZonesDropzone" class="dropzone"></div>
        </div>
        <select class="form-select mt-3" name="idSchool" id="idSchool">
            <option value="" disabled selected>Seleccione una escuela</option>
            <?php foreach ($schools as $school) :?>
                <option value="<?php echo $school['idSchool'];?>"><?php echo $school['nameSchool'];?></option>
            <?php endforeach;?>
        </select>
        <input type="hidden" id="zone" value="1">
        
        <div class="d-flex justify-content-center my-3">
            <button type="button" class="btn btn-danger me-2" onclick="closeMenu('modalZones')">Cancelar</button>
            <button type="button" class="btn btn-success sendZones" disabled>Aceptar</button>
        </div>
    </form>
</div>

<script src="view/assets/js/ajax/addZones.js"></script>
