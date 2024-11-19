<!-- Incluir la librería de Dropzone desde una CDN o desde tu servidor -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
    Dropzone.autoDiscover = false; // Desactivar autodetección
</script>

<div class="container p-4">
    <!-- Encabezado con el nombre del area -->
    <div class="p-3 mb-4 rounded">
        <h5 id="namePage" class="page-title"></h5>
    </div>
    <!-- boton para agregar objetos -->
    <div class="d-flex items-center ms-auto my-3 mt-sm-0">
        <button class="btn btn-primary" onclick="openMenu('modalObjects', 'newObjectForm')">
            <i class="fa-duotone fa-plus"></i> Agregar objeto
        </button>
    </div>
    <input type="hidden" id="area" value="<?php echo $_GET['area'] ?>">
    <!-- tabla de objetos en áreas -->
     <table id="objects" class="table table-striped table-hover">
         <thead>
             <tr>
                 <th>#</th>
                 <th>Nombre del objeto</th>
                 <th>Cantidad</th>
                 <th></th>
             </tr>
         </thead>
     </table>
</div>

<!-- Modal para agregar objetos -->
<div class="modal-collapse" id="modalObjects">
    <!-- Encabezado del modal -->
    <div class="modal-header">
        <h5 class="modal-title">Agregar objeto</h5>
    </div>
    <!-- Contenido del modal -->
    <div class="modal-body">
        <!-- Formulario para agregar objetos -->
        <form id="newObjectForm">
            <div class="mb-3">
                <label for="nameObject" class="form-label">Nombre del objeto</label>
                <input type="text" class="form-control" id="nameObject" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary" id="addObject">Agregar Objeto</button>
        </form>
        
        <div class="intro-y box d-none moduleAddObjects">
            <form class="p-1" id="newObjects">
                <!-- Botón para descargar plantilla de ejemplo -->
                <div class="form-group my-3">
                    <a type="button" class="btn btn-link" download="objects_template.csv" href="view/assets/templates/objects_template.csv">Descargar plantilla</a>
                </div>
                <!-- Dropzone para cargar archivos -->
                <div class="col-md-12">
                    <div id="addObjectsDropzone" class="dropzone"></div>
                </div>
                
                <div class="d-flex justify-content-center my-3">
                    <button type="button" class="btn btn-danger me-2 cancelMassiveObjects">Cancelar</button>
                    <button type="button" class="btn btn-success sendObjects" disabled>Aceptar</button>
                </div>
            </form>
        </div>

        <div class="d-grid gap-2 addObjects">
            <div class="btn-group">
                <button type="button" class="btn btn-link my-3 addMassiveObject"><i class="fad fa-folder-plus"></i> Registro masivo con archivo</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal para actualizar objetos -->
<div class="modal-collapse" id="modalObjectsUpdate">
    <!-- Encabezado del modal -->
    <div class="modal-header">
        <h5 class="modal-title">Actualizar objeto</h5>
    </div>
    <!-- Contenido del modal -->
    <div class="modal-body">
        <!-- Formulario para actualizar objetos -->
        <form id="newObjectForm">
            <div class="mb-3">
                <label for="nameObject" class="form-label">Nombre del objeto</label>
                <input type="text" class="form-control" id="nameObjectUpdate" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="quantityUpdate" required>
            </div>

            <button type="button" class="btn btn-danger" id="cancel">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="update">Actualizar Objeto</button>
        </form>
        
    </div>
</div>

<script src="view/assets/js/ajax/Objects/getObjects.js"></script>