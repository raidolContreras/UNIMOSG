<div class="container p-4">
    <!-- Encabezado con el nombre del piso -->
    <div class="p-3 mb-4 rounded">
        <h5 id="namePage" class="page-title">Gestión de Pisos</h5>
    </div>

    <!-- Tarjetas de Zonas -->
    <div id="zoneSelection" class="row text-center">
        <div class="col-6 col-md-3 mb-4">
            <div class="card zone-card" data-zone="Norte">
                <div class="card-body">
                    <h5 class="card-title">Norte</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <div class="card zone-card" data-zone="Sur">
                <div class="card-body">
                    <h5 class="card-title">Sur</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <div class="card zone-card" data-zone="Este">
                <div class="card-body">
                    <h5 class="card-title">Este</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <div class="card zone-card" data-zone="Oeste">
                <div class="card-body">
                    <h5 class="card-title">Oeste</h5>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="floor" value="<?php echo $_GET['floor'] ?>">
    
    <!-- Contenedor Dinámico de Áreas -->
    <div id="zoneAreas" class="d-none">
        <h3 class="text-center mb-4" id="zoneTitle"></h3>
        <!-- Botón para abrir el modal de agregar área -->
        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addAreaModal">Agregar área</button>
        <table id="areasTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Nombre del Área</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Áreas se cargarán aquí -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar área -->
<div class="modal fade" id="addAreaModal" tabindex="-1" aria-labelledby="addAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAreaModalLabel">Agregar Nueva área</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAreaForm">
                    <div class="mb-3">
                        <label for="areaName" class="form-label">Nombre del área</label>
                        <input type="text" class="form-control" id="areaName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal-collapse" id="modalNavUpdate">
    <span class="close-btn" onclick="closeMenu('modalNavUpdate')">&times;</span>
    <div class="modal-nav">
        <?php include "view/pages/admin/zones/editAreas.php"; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteAreas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Eliminar Area</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Esta usted seguro que desea eliminar el area?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success delete">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script src="view/assets/js/ajax/Zones/getAreas.js"></script>