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
        <!-- Botón para abrir el modal de agregar área -->
        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addAreaModal">Agregar área</button>
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

<script>
    let currentZone = "";
    let areasTable;

    $(document).ready(function() {
        // Inicializar DataTable con AJAX
        areasTable = $('#areasTable').DataTable({
            rowReorder: {
                dataSrc: 'position',
                selector: 'td:first-child'
            },
            ajax: {
                "url": "controller/forms.ajax.php",
                "type": "POST",
                "data": function(d) {
                    // Agregar parámetros adicionales al AJAX
                    d.action = 'getAreasForZone';
                    d.zone = currentZone;
                    d.idFloor = $('#floor').val();
                },
                "dataSrc": "" // Espera un array de objetos como respuesta
            },
            columns: [
                {
                    data: 'position',
                    render: () => `<center style="cursor: grab;"><i class="fas fa-sort"></i></center>`,
                    orderable: false // Desactiva el ordenamiento en esta columna
                },
                {
                    data: null,
                    render: (data) => `
                        <center>
                            <button class="btn btn-link" onclick="openArea(${data.idArea})">
                                <span class="arrow">${data.nameArea}</span>
                            </button>
                        </center>`
                },
                {
                    data: null,
                    render: (data) => `
                        <center>
                            <div class="btn-group">
                                <button class="btn btn-info" onclick="openMenuEdit(${data.idArea})" data-tippy-content="Editar">
                                    <i class="fa-duotone fa-pen-to-square"></i>
                                </button>
                                <button class="btn btn-danger" onclick="showModal(${data.idArea})" data-tippy-content="Eliminar">
                                    <i class="fa-duotone fa-trash"></i>
                                </button>
                            </div>
                        </center>`
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            }
        });

        // Manejar clic en la tarjeta de zona
        $(".zone-card").on("click", function() {
            currentZone = $(this).data("zone");

            // Mostrar y actualizar la vista de áreas de zona
            $("#zoneAreas").removeClass("d-none");
            $("#zoneTitle").text(`Zona - ${currentZone}`);

            // Remover clase activa de todas las tarjetas y agregarla a la seleccionada
            $(".zone-card").removeClass("active-zone");
            $(this).addClass("active-zone");

            // Recargar los datos en el DataTable
            areasTable.ajax.reload();
        });

        // Manejar el envío del formulario para agregar área
        $("#addAreaForm").on("submit", function(e) {
            e.preventDefault();

            const areaName = $("#areaName").val().trim();
            if (areaName) {
                $.ajax({
                    url: 'controller/forms.ajax.php',
                    type: 'POST',
                    data: {
                        action: 'registerArea',
                        zone: currentZone,
                        areaName: areaName,
                        idFloor: $('#floor').val()
                    },
                    success: function(response) {
                        if (response == 'ok') {
                            // Recargar el DataTable después de agregar el área
                            areasTable.ajax.reload();
                            $("#areaName").val(""); // Limpiar el campo
                        } else {
                            alert("Error al agregar el área: " + response.error);
                        }
                    },
                    error: function() {
                        alert("Error en la solicitud AJAX.");
                    }
                });
            }
        });
        
    
        // Evento para manejar el reordenamiento
        areasTable.on('row-reorder', function (e, diff, edit) {
            var orden = [];
            diff.forEach(function (change) {
                orden.push({
                    id: areasTable.row(change.node).data().idArea,
                    position: change.newPosition + 1
                });
            });

            // Enviar el nuevo orden al servidor
            $.ajax({
                type: "POST",
                url: 'controller/forms.ajax.php',
                data: {
                    action: 'updateOrderArea',
                    order: orden
                },
                success: function(response) {
                    if(response === 'ok') {
                        areasTable.ajax.reload(); // Recargar la tabla para reflejar los cambios
                    } else {
                        console.error('Error al actualizar el orden:', response);
                    }
                }
            });
        });
    });

    function openMenuEdit(idArea) {
        
        openMenu('modalNavUpdate', 'editAreas');

        $.ajax({
            url: "controller/forms.ajax.php",
            type: "POST",
            data: { action: "searchArea", searchArea: idArea },
            dataType: "json",
            success: function (response) {
                
                $('#nameAreaEdit').val(response.nameArea);

                $('.update').off('click').on('click', function () {
                    $.ajax({
                        url: "controller/forms.ajax.php",
                        type: "POST",
                        data: {
                            action: "updateArea",
                            idArea: response.idArea,
                            areaName: $('#nameAreaEdit').val()
                        },
                        success: function () {
                            areasTable.ajax.reload();
                            closeMenu('modalNavUpdate');
                        },
                        error: function (xhr, status, error) {
                            alert("Error al actualizar el edificio: " + error);
                        }
                    });
                });
            }
        });

    }
    
    function showModal(idArea) {
        $('#deleteAreas').modal('show');
        $('.delete').off('click').on('click', function () {
            $.ajax({
                url: "controller/forms.ajax.php",
                type: "POST",
                data: {
                    action: "deleteArea",
                    idArea: idArea
                },
                success: function () {
                    areasTable.ajax.reload();
                    $('#deleteAreas').modal('hide');
                },
                error: function (xhr, status, error) {
                    alert("Error al eliminar el edificio: " + error);
                }
            });
        });
    }

    function openArea(idArea) {
        window.location.href = "objects&area=" + idArea;
    }
</script>