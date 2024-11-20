var level = $('#level').val();

$(document).ready(function () {
    if (typeof $.ui === 'undefined' || !$.ui.sortable) {
        $.getScript("https://code.jquery.com/ui/1.12.1/jquery-ui.min.js", function() {
            inicializarReordenamientoEdificios();
        });
    } else {
        inicializarReordenamientoEdificios();
    }

    cargarEdificios();
    

    const toggleAcceptButton = () => {
        const isNameFilled = $('#edificeName').val().trim();
        $('.saveNewEdifice').prop('disabled', !isNameFilled);
    };

    $('.saveNewEdifice').on('click', function () {
        const edificeName = $('#edificeName').val().trim();
        const idSchool = $('#school').val();

        if (edificeName) {
            $.ajax({
                url: "controller/forms.ajax.php",
                type: "POST",
                data: {
                    action: "registerEdificer",
                    edificeName,
                    idSchool
                },
                success: function () {
                    alert("Edificio creado correctamente");
                    $('#edificeName, #selectSchool').val('');
                    $('.saveNewEdifice').prop('disabled', true);
                    cargarEdificios();
                    closeMenu('modalEdifices');
                },
                error: function (xhr, status, error) {
                    alert("Error al crear el edificio: " + error);
                }
            });
        }
    });

    $('#edificeName').on('keyup', toggleAcceptButton);
    $('#selectSchool').on('change', toggleAcceptButton);
});


function cargarEdificios() {
    $.ajax({
        type: 'POST',
        url: 'controller/forms.ajax.php',
        data: {
            action: 'searchEdificer',
            idSchool: $('#school').val()
        },
        success: function(response) {
            // Asegúrate de que `response` es un objeto JSON
            var data = JSON.parse(response);
            var edificers = data.edificers || []; // Accede al array 'edificers'
            // link de regresar a la lista de escuelas
            
            $('#namePage').html(`
                <a href="schools" class="btn btn-link" >Planteles</a>
                    <i class="fal fa-angle-right"></i>
                <a class="btn btn-link disabled">${data.schoolName}</a>`);
            
            $('#edificesContainer').empty();
            edificers.forEach(function (edificio) {
                var level = $('#level').val();
                if ( level == 0 ){
                var edificioCard = `
                <div class="col-md-4 mb-3">
                    <div class="card edificio-item shadow-sm border-0" data-position="${edificio.position}" style="align-items: center; flex-direction: row;">
                        <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div class="handle me-3">
                                <i class="fas fa-grip-vertical fa-lg text-muted"></i>
                            </div>
                            <button class="btn btn-link p-0 text-dark fw-bold flex-grow-1 text-start" onclick="openEdifices(${edificio.idEdificers})" style="text-decoration: none;">
                                <span class="arrow">${edificio.nameEdificer}</span>
                            </button>
                        </div>
                        <div class="dropdown ms-3">
                            <button style="margin-right: 15px;" class="btn btn-link text-dark p-0" type="button" id="dropdownMenuButton${edificio.idEdificers}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton${edificio.idEdificers}">
                                <li><button class="dropdown-item" onclick="openMenuEdit(${edificio.idEdificers})">Editar</button></li>
                                <li><button class="dropdown-item text-danger" onclick="showModal(${edificio.idEdificers})">Eliminar</button></li>
                            </ul>
                        </div>
                    </div>
                </div>`;
                } else {
                    var edificioCard = `
                    <div class="col-md-4 mb-3">
                        <div class="card edificio-item shadow-sm border-0" data-position="${edificio.position}" style="align-items: center; flex-direction: row;">
                            <div class="card-body d-flex justify-content-between align-items-center p-3">
                                <button class="btn btn-link p-0 text-dark fw-bold flex-grow-1 text-start" onclick="openEdifices(${edificio.idEdificers})" style="text-decoration: none;">
                                    <span class="arrow">${edificio.nameEdificer}</span>
                                </button>
                            </div>
                        </div>
                    </div>`;
                }
                $('#edificesContainer').append(edificioCard);
            });
            inicializarReordenamientoEdificios();
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar los edificios:", error);
        }
    });
}


function inicializarReordenamientoEdificios() {
    $('#edificesContainer').sortable({
        handle: '.handle',
        update: function (event, ui) {
            var orden = [];
            $('.edificio-item').each(function (index) {
                orden.push({
                    id: $(this).find('button.btn-link').attr('onclick').match(/\d+/)[0],
                    position: index + 1
                });
            });
            $.ajax({
                type: "POST",
                url: 'controller/forms.ajax.php',
                data: {
                    action: 'updateOrderEdificer',
                    order: orden
                },
                success: function(response) {
                    if(response === 'ok') {
                        cargarEdificios(); // Recargar la lista para reflejar los cambios
                    } else {
                        console.error('Error al actualizar el orden:', response);
                    }
                }
            });
        }
    });
}

function openMenuEdit(idEdificer) {
    openMenu('modalNavUpdate', 'editEdifices');

    $.ajax({
        url: "controller/forms.ajax.php",
        type: "POST",
        data: { action: "searchEdificer", searchEdificer: idEdificer },
        dataType: "json",
        success: function (response) {
            $('#nameEdificeEdit').val(response.nameEdificer);

            $('.update').off('click').on('click', function () {
                $.ajax({
                    url: "controller/forms.ajax.php",
                    type: "POST",
                    data: {
                        action: "updateEdificer",
                        idEdificers: response.idEdificers,
                        edificeName: $('#nameEdificeEdit').val()
                    },
                    success: function () {
                        alert("Edificio actualizado correctamente");
                        cargarEdificios();
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

function showModal(idEdificers) {
    $('#deleteEdificers').modal('show');
    $('.delete').off('click').on('click', function () {
        $.ajax({
            url: "controller/forms.ajax.php",
            type: "POST",
            data: {
                action: "deleteEdificer",
                idEdificers: idEdificers
            },
            success: function () {
                alert("Edificio eliminado correctamente");
                cargarEdificios();
                $('#deleteEdificers').modal('hide');
            },
            error: function (xhr, status, error) {
                alert("Error al eliminar el edificio: " + error);
            }
        });
    });
}

function openEdifices(idEdificers) {
    window.location.href = "floors&edificer=" + idEdificers;
}