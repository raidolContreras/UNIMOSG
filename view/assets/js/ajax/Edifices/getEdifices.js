$(document).ready(function () {
    const tablaEdificio = $('#edifices').DataTable({
        rowReorder: {
            dataSrc: 'position',
            selector: 'td:first-child'
        },
        ajax: {
            type: 'POST',
            data: { 
                action: 'searchEdificer',
                idSchool: $('#school').val()
            },
            url: 'controller/forms.ajax.php',
            dataSrc: function (json) {
                // Muestra el nombre de la escuela en el encabezado o algún lugar de la interfaz
                $('#namePage').text('Edificios registrados - '+json.schoolName || "Escuela no especificada");
                return json.edificers || []; // Solo pasa el array 'edificers' a DataTable
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
                        <button class="btn btn-link" onclick="openEdifices(${data.idEdificers})">
                            <span class="arrow">${data.nameEdificer}</span>
                        </button>
                    </center>`
            },
            {
                data: null,
                render: (data) => `
                    <center>
                        <div class="btn-group">
                            <button class="btn btn-info" onclick="openMenuEdit(${data.idEdificers})" data-tippy-content="Editar">
                                <i class="fa-duotone fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger" onclick="showModal(${data.idEdificers})" data-tippy-content="Eliminar">
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
                    tablaEdificio.ajax.reload();
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

    $('.addMassiveEdifice').click(function () {
        $('.moduleAddEdifices').removeClass('d-none');
        $('.newEdificeForm, .addEdifices').addClass('d-none');

        if (!myDropzone) {
            myDropzone = new Dropzone("#addEdificesDropzone", {
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

    $('.cancelMassiveEdifices').click(function () {
        $('.moduleAddEdifices').addClass('d-none');
        $('.newEdificeForm, .addEdifices').removeClass('d-none');

        if (myDropzone) {
            myDropzone.destroy();
            myDropzone = null;
        }
    });
    
    // Evento para manejar el reordenamiento
    tablaEdificio.on('row-reorder', function (e, diff, edit) {
        var orden = [];
        diff.forEach(function (change) {
            orden.push({
                id: tablaEdificio.row(change.node).data().idEdificers,
                position: change.newPosition + 1
            });
        });

        // Enviar el nuevo orden al servidor
        $.ajax({
            type: "POST",
            url: 'controller/forms.ajax.php',
            data: {
                action: 'updateOrderEdificer',
                order: orden
            },
            success: function(response) {
                if(response === 'ok') {
                    tablaEdificio.ajax.reload(); // Recargar la tabla para reflejar los cambios
                } else {
                    console.error('Error al actualizar el orden:', response);
                }
            }
        });
    });
});

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
                        $('#edifices').DataTable().ajax.reload();
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
                $('#edifices').DataTable().ajax.reload();
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