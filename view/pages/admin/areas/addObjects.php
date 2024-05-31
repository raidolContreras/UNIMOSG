<script src="view/assets/vendor/dropzone/dropzone-min.js"></script>
<link href="view/assets/vendor/dropzone/dropzone.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jeditable/1.7.3/jquery.jeditable.min.js"></script>

<div class="intro-y box">
    <form class="p-5" id="newObjets">
        <center>
            <h3 class="nameschool"></h3>
        </center>
        <!-- Botón para descargar plantilla de ejemplo -->
        <div class="form-group my-3">
            <a type="button" class="btn btn-link" download="objects_template.csv" href="view/assets/templates/objects_template.csv">Descargar plantilla</a>
        </div>
        <!-- Dropzone para cargar archivos -->
        <div class="col-md-12">
            <div id="addObjectsDropzone" class="dropzone"></div>
        </div>
        <input type="hidden" class="idReference" id="idArea">
        
        <div class="d-flex justify-content-center my-3">
            <button type="button" class="btn btn-danger me-2" onclick="closeMenu('modalObjects')">Cancelar</button>
            <button type="button" class="btn btn-success sendObjects" disabled>Aceptar</button>
        </div>
    </form>
</div>

<script src="view/assets/js/ajax/addObjects.js"></script>
