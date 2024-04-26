<script src="view/assets/vendor/dropzone/dropzone-min.js"></script>
<link href="view/assets/vendor/dropzone/dropzone.css" rel="stylesheet">

<div class="intro-y box">
    <form class="p-5" id="newAreas">
        <center>
            <h3 class="nameschool"></h3>
        </center>
        <!-- Botón para descargar plantilla de ejemplo -->
        <div class="form-group my-3">
            <a type="button" class="btn btn-link" download="areas_template.csv" href="view/assets/templates/areas_template.csv">Descargar plantilla</a>
        </div>
        <!-- Dropzone para cargar archivos -->
        <div class="col-md-12">
            <div id="addZonesDropzone" class="dropzone"></div>
        </div>
        <input type="hidden" class="idReference" id="idZone">
        
        <div class="d-flex justify-content-center my-3">
            <button type="button" class="btn btn-danger me-2" onclick="closeMenu('modalCollapse')">Cancelar</button>
            <button type="button" class="btn btn-success sendZones" disabled>Aceptar</button>
        </div>
    </form>
</div>

<script src="view/assets/js/ajax/addAreas.js"></script>
