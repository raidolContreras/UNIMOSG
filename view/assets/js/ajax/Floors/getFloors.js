$(document).ready(function () {
    const tablaPisos = $('#floors').DataTable({
        rowReorder: {
            dataSrc: 'position',
            selector: 'td:first-child'
        },
        ajax: {
            type: 'POST',
            data: { 
                action: 'searchFloor',
                idEdificers: $('#edificer').val()
            },
            url: 'controller/forms.ajax.php',
            dataSrc: function (json) {
                $('#namePage').text('Pisos registrados - ' + json.nameSchool + ' - ' + json.EdificersName || "Edificio no especificado");
                return json.floors || [];
            }
        },
        ordering: false,
        columns: [
            {
                data: 'position',
                render: () => `<center style="cursor: grab;"><i class="fas fa-sort"></i></center>`,
            },
            {
                data: null,
                render: (data) => `
                    <center>
                        <button class="btn btn-link" onclick="openFloors(${data.idFloor})">
                            <span class="arrow">${data.nameFloor}</span>
                        </button>
                    </center>`
            },
            {
                data: null,
                render: (data) => `
                    <center>
                        <div class="btn-group">
                            <button class="btn btn-info" onclick="openMenuEdit(${data.idFloor})" data-tippy-content="Editar">
                                <i class="fa-duotone fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger" onclick="showModal(${data.idFloor})" data-tippy-content="Eliminar">
                                <i class="fa-duotone fa-trash"></i>
                            </button>
                        </div>
                    </center>`
            }
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        },
        drawCallback: function () {
            tippy('[data-tippy-content]', {
                duration: 0,
                arrow: false,
                delay: [1000, 200],
                followCursor: true,
            });
        }
    });    

    let myDropzone = null;

    const toggleAcceptButton = () => {
        const isNameFilled = $('#floorName').val().trim();
        $('.saveNewFloor').prop('disabled', !isNameFilled);
    };

    $('.saveNewFloor').on('click', function () {
        const floorName = $('#floorName').val().trim();
        const idEdificers = $('#edificer').val();

        if (floorName) {
            $.ajax({
                url: "controller/forms.ajax.php",
                type: "POST",
                data: {
                    action: "registerFloor",
                    floorName,
                    idEdificers
                },
                success: function () {
                    alert("Piso creado correctamente");
                    $('#floorName').val('');
                    $('.saveNewFloor').prop('disabled', true);
                    tablaPisos.ajax.reload();
                    closeMenu('modalFloors');
                },
                error: function (xhr, status, error) {
                    alert("Error al crear el piso: " + error);
                }
            });
        }
    });

    $('#floorName').on('keyup', toggleAcceptButton);

    $('.addMassiveFloor').click(function () {
        $('.moduleAddFloors').removeClass('d-none');
        $('.newFloorForm, .addFloors').addClass('d-none');

        if (!myDropzone) {
            myDropzone = new Dropzone("#addFloorsDropzone", {
                url: "/ruta_de_subida",
                acceptedFiles: ".xls,.xlsx",
                init: function () {
                    this.on("success", function () {
                        alert("Archivo subido correctamente");
                    });
                }
            });
        }
    });

    $('.cancelMassiveFloors').click(function () {
        $('.moduleAddFloors').addClass('d-none');
        $('.newFloorForm, .addFloors').removeClass('d-none');

        if (myDropzone) {
            myDropzone.destroy();
            myDropzone = null;
        }
    });
    
    // Evento para manejar el reordenamiento
    tablaPisos.on('row-reorder', function (e, diff, edit) {
        var orden = [];
        diff.forEach(function (change) {
            orden.push({
                id: tablaPisos.row(change.node).data().idFloor,
                position: change.newPosition + 1
            });
        });

        // Enviar el nuevo orden al servidor
        $.ajax({
            type: "POST",
            url: 'controller/forms.ajax.php',
            data: {
                action: 'updateOrderFloor',
                order: orden
            },
            success: function(response) {
                if(response === 'ok') {
                    tablaPisos.ajax.reload();
                } else {
                    console.error('Error al actualizar el orden:', response);
                }
            }
        });
    });
});

function openMenuEdit(idFloor) {
    openMenu('modalNavUpdate', 'editFloors');

    $.ajax({
        url: "controller/forms.ajax.php",
        type: "POST",
        data: { action: "searchFloor", searchFloor: idFloor },
        dataType: "json",
        success: function (response) {
            $('#nameFloorEdit').val(response.nameFloor);

            $('.update').off('click').on('click', function () {
                $.ajax({
                    url: "controller/forms.ajax.php",
                    type: "POST",
                    data: {
                        action: "updateFloor",
                        idFloor: response.idFloor,
                        floorName: $('#nameFloorEdit').val()
                    },
                    success: function () {
                        alert("Piso actualizado correctamente");
                        $('#floors').DataTable().ajax.reload();
                        closeMenu('modalNavUpdate');
                    },
                    error: function (xhr, status, error) {
                        alert("Error al actualizar el piso: " + error);
                    }
                });
            });
        }
    });
}

function showModal(idFloor) {
    $('#deleteFloors').modal('show');
    $('.delete').off('click').on('click', function () {
        $.ajax({
            url: "controller/forms.ajax.php",
            type: "POST",
            data: {
                action: "deleteFloor",
                idFloor: idFloor
            },
            success: function () {
                alert("Piso eliminado correctamente");
                $('#floors').DataTable().ajax.reload();
                $('#deleteFloors').modal('hide');
            },
            error: function (xhr, status, error) {
                alert("Error al eliminar el piso: " + error);
            }
        });
    });
}

function openFloors(idFloor) {
    window.location.href = "zones&floor=" + idFloor;
}
