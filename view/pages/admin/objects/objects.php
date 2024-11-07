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
            <button type="submit" class="btn btn-primary" id="update">Actualizar Objeto</button>
        </form>

    </div>
</div>

<script src="view/assets/js/ajax/Objects/getObjects.js"></script>