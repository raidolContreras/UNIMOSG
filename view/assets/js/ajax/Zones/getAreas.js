
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
                d.action = 'getAreasForZone';
                d.zone = currentZone;
                d.idFloor = $('#floor').val();
            },
            dataSrc: function (data) {
                // Actualizar el nombre de la página
                
                $('#namePage').html(`
                        <a href="schools" class="btn btn-link" >Inicio</a>
                            <i class="fal fa-angle-right"></i> 
                        <a href="edifices&school=${data.idSchool}" class="btn btn-link" >${data.nameSchool} </a>
                            <i class="fal fa-angle-right"></i>
                        <a href="floors&edificer=${data.idEdificers}" class="btn btn-link" >${data.EdificersName}</a>
                            <i class="fal fa-angle-right"></i>
                        <a class="btn btn-link disabled">${data.nameFloor}</a>`);
                
                // Retornar los datos de áreas
                return data.areas || [];
            }
        },
        ordering: false,
        columns: [
            {
                data: 'position',
                render: () => `<center class="handle"><i class="fal fa-sort"></i></center>`,
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
        language: {
            "paginate": {
                "first": '<i class="fal fa-angle-double-left"></i>',
                "last": '<i class="fal fa-angle-double-right"></i>',
                "next": '<i class="fal fa-angle-right"></i>',
                "previous": '<i class="fal fa-angle-left"></i>'
            },
            "search": "Buscar:",
            "lengthMenu": "Ver _MENU_ resultados",
            "loadingRecords": "Cargando...",
            "info": "Mostrando _START_ de _END_ en _TOTAL_ resultados",
            "infoEmpty": "Mostrando 0 resultados",
			"emptyTable":	  "Ningún dato disponible en esta tabla"
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