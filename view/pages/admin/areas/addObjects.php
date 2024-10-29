<script src="view/assets/vendor/dropzone/dropzone-min.js"></script>
<link href="view/assets/vendor/dropzone/dropzone.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jeditable/1.7.3/jquery.jeditable.min.js"></script>

<div class="intro-y box d-none objectsBox">
    <form class="p-1" id="newObjets">
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
            <button type="button" class="btn btn-danger me-2 cancelObject">Cancelar</button>
            <button type="button" class="btn btn-success sendObjects" disabled>Aceptar</button>
        </div>
    </form>
</div>

<!-- Formulario para añadir nuevos objetos -->
<form id="newObjectForm2" class="my-3">
    <div class="form-group">
        <label for="nameObject">Nombre del objeto</label>
        <input type="text" class="form-control" id="nameObject" placeholder="Ingrese el nombre del objeto">
    </div>
    <div class="form-group mb-3">
        <label for="countObjects">Cantidad</label>
        <input type="number" class="form-control" id="countObjects" placeholder="Ingrese la cantidad">
    </div>
    <div class="d-flex justify-content-center my-3">
        <button type="button" class="btn btn-success saveNewObject" disabled>Aceptar</button>
    </div>
</form>

<div class="d-grid gap-2">
    Para editar un objeto, sigue estos pasos:
    <ol>
        <li>Haz clic en el objeto de la lista que deseas editar.</li>
        <li>Modifica el nombre del objeto en el campo de texto que aparece.</li>
        <li>Haz clic en el botón verde (✔) para guardar los cambios o en el botón rojo (✖) para cancelar.</li>
    </ol>
    <div class="btn-group addObjects">
        <button type="button" class="btn btn-link my-3 addMassiveObject"><i class="fad fa-folder-plus"></i> Añadir objetos</button>
    </div>
</div>

<script src="view/assets/js/ajax/addObjects.js"></script>
