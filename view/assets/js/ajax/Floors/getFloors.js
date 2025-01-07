var level = $('#level').val();

$(document).ready(function () {
    if (typeof $.ui === 'undefined' || !$.ui.sortable) {
        $.getScript("https://code.jquery.com/ui/1.12.1/jquery-ui.min.js", function() {
            inicializarReordenamientoPisos();
        });
    } else {
        inicializarReordenamientoPisos();
    }

    cargarPisos();

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
                    cargarPisos();
                    closeMenu('modalFloors');
                },
                error: function (xhr, status, error) {
                    alert("Error al crear el piso: " + error);
                }
            });
        }
    });

    $('#floorName').on('keyup', toggleAcceptButton);
});

function cargarPisos() {
    $.ajax({
        type: 'POST',
        url: 'controller/forms.ajax.php',
        data: {
            action: 'searchFloor',
            idEdificers: $('#edificer').val()
        },
        success: function(response) {
            var data = JSON.parse(response);
            var floors = data.floors || [];
            
            $('#namePage').html(`
                        <a href="schools" class="btn btn-link" >Inicio</a>
                            <i class="fal fa-angle-right"></i> 
                        <a href="edifices&school=${data.idSchool}" class="btn btn-link" >${data.nameSchool} </a>
                            <i class="fal fa-angle-right"></i>
                        <a class="btn btn-link disabled">${data.EdificersName}</a>`);

            $('#floorsContainer').empty();
            floors.forEach(function (floor) {
                var level = $('#level').val();
                if ( level == 0 ){
                    var floorCard = `
                    <div class="col-md-4 mb-3">
                        <div class="card floor-item shadow-sm border-0" data-position="${floor.position}" style="align-items: center; flex-direction: row;">
                            <div class="card-body d-flex justify-content-between align-items-center p-3">
                                <div class="handle me-3">
                                    <i class="fas fa-grip-vertical fa-lg text-muted"></i>
                                </div>
                                <button class="btn btn-link p-0 text-dark fw-bold flex-grow-1 text-start" onclick="openFloors(${floor.idFloor})" style="text-decoration: none;">
                                    <span class="arrow">${floor.nameFloor}</span>
                                </button>
                            </div>
                            <div class="dropdown ms-3">
                                <button style="margin-right: 15px;" class="btn btn-link text-dark p-0" type="button" id="dropdownMenuButton${floor.idFloor}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                                    <i class="fas fa-ellipsis-v fa-lg"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton${floor.idFloor}">
                                    <li><button class="dropdown-item" onclick="openMenuEdit(${floor.idFloor})">Editar</button></li>
                                    <li><button class="dropdown-item text-danger" onclick="showModal(${floor.idFloor})">Eliminar</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>`;
                } else {
                    var floorCard = `
                    <div class="col-md-4 mb-3">
                        <div class="card floor-item shadow-sm border-0" data-position="${floor.position}" style="align-items: center; flex-direction: row;">
                            <div class="card-body d-flex justify-content-between align-items-center p-3">
                                <button class="btn btn-link p-0 text-dark fw-bold flex-grow-1 text-start" onclick="openFloors(${floor.idFloor})" style="text-decoration: none;">
                                    <span class="arrow">${floor.nameFloor}</span>
                                </button>
                            </div>
                        </div>
                    </div>`;
                }

                $('#floorsContainer').append(floorCard);
            });

            inicializarReordenamientoPisos();
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar los pisos:", error);
        }
    });
}

function inicializarReordenamientoPisos() {
    $('#floorsContainer').sortable({
        handle: '.handle',
        update: function (event, ui) {
            var orden = [];
            $('.floor-item').each(function (index) {
                orden.push({
                    id: $(this).find('button.btn-link').attr('onclick').match(/\d+/)[0],
                    position: index + 1
                });
            });
            $.ajax({
                type: "POST",
                url: 'controller/forms.ajax.php',
                data: {
                    action: 'updateOrderFloor',
                    order: orden
                },
                success: function(response) {
                    if(response === 'ok') {
                        cargarPisos(); // Recargar la lista para reflejar los cambios
                    } else {
                        console.error('Error al actualizar el orden:', response);
                    }
                }
            });
        }
    });
}

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
                        cargarPisos();
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
                cargarPisos();
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
