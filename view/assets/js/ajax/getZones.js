$(document).ready(function() {
    var school = $('#school').val();
    
    var table = $('#zones').DataTable({
        ajax: {
            type: "POST",
            url: 'controller/ajax/getZones.php',
            data: { idSchool: school },
            dataSrc: '',
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return `
                    <center class="table-columns">
                        ${meta.row + 1}
                    </center>
                    `;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                    <center class="table-columns">
                        ` + data.nameZone + `
                    </center>
                    `;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                    <center class="table-columns">
                        ` + data.nameSchool + `
                    </center>
                    `;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                    <center class="table-columns">
                        <div class="flex justify-center items-center btn-group">
                            <button class="btn btn-primary items-center button-custom" onclick="verArea(${data.idZone})" data-tippy-content="Ver areas">
                                <i class="fa-duotone fa-right-from-bracket"></i>
                            </button>
                            <button class="btn btn-info items-center button-custom" onclick="openMenuEdit('modalNavUpdate', 'editZones', ${data.idZone})" data-tippy-content="Editar">
                                <i class="fa-duotone fa-pen-to-square"></i>                            </button>
                            <button class="btn btn-danger items-center button-custom" onclick="deleteZone(${data.idZone})" data-tippy-content="Eliminar">
                                <i class="fa-duotone fa-trash"></i> 
                            </button>
                        </div>
                    </center>
                    `;
                }
            }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        },
        // Aquí se inicializan los tooltips después de renderizar la tabla
        drawCallback: function() {
            tippy('[data-tippy-content]', {
                duration: 0,
                arrow: false,
                delay: [1000, 200],
                followCursor: true,
            });            
        }
    });
});

function openMenuEdit(collapse, idForm, id) {
    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            searchZone: id
        },
        dataType: 'json',
        success: function(data) {
            $('#nameZoneEdit').val(data.nameZone);
            $('#edit').val(data.idZone);

            document.querySelector('#' + collapse).classList.add('show');
            var modalBackdrop = document.createElement('div');
            modalBackdrop.classList.add('modal-backdrop', 'fade', 'show');
            document.body.appendChild(modalBackdrop);
        }
    });
}

function verArea(idZone) {
    var form = document.createElement('form');
    form.method = 'post';
    form.action = 'areas'; 

    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'zone';
    input.value = idZone;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

function deleteZone(idZone) {
    var html = `
        <p>
            ¿Está seguro de eliminar la zona?
        </p>
    `;
    $('.titleEvent').html(html);
    $('.contentDeleteModal').html('Esta acción no se puede revertir');
    $('#deleteModal').modal('show');
    $('#modalDeleteButton').attr('onclick', 'confirmDeleteZone(' + idZone + ')');
}

function confirmDeleteZone(idZone) {
    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            deleteZone: idZone
        },
        success: function(data) {
            $('#deleteModal').modal('hide');
            if (data === 'ok') {
                alert('Zona eliminada correctamente');
                
                // Recargar el DataTable después de la eliminación
                var table = $('#zones').DataTable();
                table.ajax.reload(null, false);  // 'false' para mantener la página actual
            } else {
                alert('Error al eliminar la zona');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
